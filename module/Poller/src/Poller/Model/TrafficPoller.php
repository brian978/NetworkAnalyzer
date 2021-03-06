<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Poller\Model;

use Devices\Entity\Device;
use Sniffer\Model\TrafficLogs;
use Sniffer\Object\Traffic\Connection;
use SNMP\Manager\Objects\Iface\Iface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TrafficPoller extends AbstractModel
{
    /**
     * @var SnmpPoller
     */
    protected $snmpPoller;

    /**
     * @param SnmpPoller $snmpPoller
     */
    public function __construct(SnmpPoller $snmpPoller)
    {
        $this->snmpPoller = $snmpPoller;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        parent::setServiceLocator($serviceLocator);

        // Injecting the service locator in the SnmpPoller also
        $this->snmpPoller->setServiceLocator($serviceLocator);
    }

    /**
     * @param bool   $doLogging
     * @param int    $deviceId
     * @param string $interfaceName
     * @return mixed
     */
    public function tcpPoll($doLogging = true, $deviceId = 0, $interfaceName = '')
    {
        $connections = array();
        $devices     = $this->snmpPoller->bandwidthPoll(false, $deviceId);

        if ($interfaceName !== '') {
            if (isset($devices[$deviceId])) {

                $interface = $devices[$deviceId]['device']->getInterfacesByName($interfaceName);

                /** @var $deviceEntity Device */
                $deviceEntity = $devices[$deviceId]['device']->getDeviceEntity();

                // Getting the connections for the requested interface
                if ($this->isServerAvailable($deviceEntity->getInterface()->getIp())) {
                    if (count($interface) == 1) {
                        $interface              = current($interface);
                        $connections[$deviceId] = $this->getConnections($interface);
                    }
                }
            }
        } else {

            // Getting the data for all the interfaces
            foreach ($devices as $id => $deviceArray) {

                $connections[$id] = array();

                /** @var $deviceEntity \Devices\Entity\Device */
                $deviceEntity = $deviceArray['device']->getDeviceEntity();

                foreach ($deviceArray['device']->getInterfaces() as $interface) {

                    $interfaceIp = $interface->getIp()->get();
                    if ($interfaceIp == '127.0.0.1' || empty($interfaceIp)) {
                        continue;
                    }

                    if ($this->isServerAvailable($deviceEntity->getInterface()->getIp())) {
                        $connections[$id] = array_merge(
                            $connections[$id],
                            $this->getConnections($interface, $deviceEntity)
                        );
                    }
                }
            }
        }

        // Logging the connections
        if ($doLogging) {
            /** @var $adapter \Zend\Db\Adapter\Adapter */
            $adapter   = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
            $logsModel = new TrafficLogs($adapter);

            foreach ($connections as $connectionArray) {
                foreach ($connectionArray as $connection) {
                    $logsModel->save($connection);
                }
            }
        }

        return $connections;
    }

    /**
     * @param Iface                  $interface
     * @param \Devices\Entity\Device $deviceEntity
     * @return array
     */
    protected function getConnections(Iface $interface, Device $deviceEntity = null)
    {
        if ($deviceEntity === null) {
            /** @var $deviceEntity \Devices\Entity\Device */
            $deviceEntity = $interface->getParentObject()->getDeviceEntity();
        }

        $connections = array();
        $ifaceName   = $interface->getName()->get();
        $output      = $this->runCommand($deviceEntity->getInterface()->getIp(), $ifaceName);

        // Processing the connections
        foreach ($output as $conn) {

            $connection = new Connection($conn);

            if ($connection->isValid()) {
                $connection->setInterface($interface);
                $connection->setDeviceEntity($deviceEntity);
                $connections[] = $connection;
            }
        }

        return $connections;
    }

    /**
     * @param $address
     * @return bool
     */
    public function isServerAvailable($address)
    {
        $jarCommand = 'java -jar proxy/dispatcher.jar -mode client -check -address ' . $address;
        $output     = shell_exec($jarCommand);

        if (trim($output) == 'Connected to server') {
            return true;
        }

        return false;
    }

    /**
     * Runs the command for a specific interface and fetches certain number of connections
     *
     * @param     $address
     * @param     $interfaceName
     * @param int $count
     * @return array
     */
    protected function runCommand($address, $interfaceName, $count = 20)
    {
        $command    = 'tcpdump -i ' . $interfaceName . ' -nqt -c ' . $count;
        $jarCommand = 'java -jar proxy/dispatcher.jar -mode client -command "' . $command . '" -address ' . $address;
        $output     = explode(chr(13) . chr(10), shell_exec($jarCommand));

        return $output;
    }
}
