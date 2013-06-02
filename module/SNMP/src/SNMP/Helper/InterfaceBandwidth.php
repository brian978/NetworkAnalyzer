<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Helper;

use Devices\Entity\Device;
use Devices\Entity\Log;
use Devices\Model\LogsModel;
use SNMP\Manager\Objects\Iface\Iface;
use Zend\Db\Adapter\Adapter;

class InterfaceBandwidth implements HelperInterface
{
    /**
     * @var BandwidthCalculator
     */
    protected $calculator;

    /**
     * @var LogsModel
     */
    protected $logsModel;

    /**
     * @param BandwidthCalculator      $calculator
     * @param \Devices\Model\LogsModel $logsModel
     */
    public function __construct(BandwidthCalculator $calculator, LogsModel $logsModel)
    {
        $this->calculator = $calculator;
        $this->logsModel  = $logsModel;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)
            && is_object($this->$name)
            && $this->$name instanceof HelperInterface
        ) {
            return call_user_func_array(array($this->$name, '__invoke'), $arguments);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        $args         = func_get_args();
        $deviceObject = $args[0];
        $device       = $args[1];

        if ($deviceObject instanceof Device == false) {
            $message = 'The device object must be of type %s, %s given';
            $message = sprintf($message, '\Devices\Entity\Device', get_class($deviceObject));
            throw new \InvalidArgumentException($message);
        }

        $deviceId = $deviceObject->getId();

        foreach ($device->getInterfaces() as $interface) {

            // Some interfaces have speed 0 so we don't need to log those
            if (intval($interface->getSpeed()->get()) > 0) {

                $logData = true;

                /**
                 * -------------------------------------
                 * GETTING DATA AND CALCULATING
                 * -------------------------------------
                 */
                $interfaceData = $this->logsModel->getLastEntries(
                    $interface->getOidIndex(),
                    $deviceId
                );

                // We need 2 logs to calculate properly
                if (!empty($interfaceData) && count($interfaceData) == 2) {

                    $last = array_shift($interfaceData);
                    $prev = array_shift($interfaceData);

                    // Queries with the same timestamp must not be saved
                    if (strpos($last['uptime'], $prev['uptime']) === 0) {
                        $logData = false;
                    }

                    // Calculating the differences
                    $diffInOctets  = intval($last['octets_in']) - intval($prev['octets_in']);
                    $diffOutOctets = intval($last['octets_out']) - intval($prev['octets_out']);
                    $diffTime      = intval($last['time']) - intval($prev['time']);

                    /**
                     * ------------------
                     * IN BANDWIDTH
                     * ------------------
                     */
                    $inData = $this->calculator($diffInOctets, $diffTime);
                    $interface->setBandwidthIn($inData['bandwidth']);
                    $interface->setBandwidthInType($inData['type']);

                    /**
                     * ------------------
                     * OUT BANDWIDTH
                     * ------------------
                     */
                    $outData = $this->calculator($diffOutOctets, $diffTime);
                    $interface->setBandwidthIn($outData['bandwidth']);
                    $interface->setBandwidthInType($outData['type']);
                }

                $this->logData(
                    $this->createLogsObject($deviceObject, $interface),
                    $logData
                );
            }
        }
    }

    /**
     * @param Device $deviceObject
     * @param Iface  $interface
     * @return Log
     */
    protected function createLogsObject(Device $deviceObject, Iface $interface)
    {
        $device = $interface->getParentObject();

        $logObject = new Log();
        $logObject->setUptime($device->getUptime());
        $logObject->setOidIndex($interface->getOidIndex());
        $logObject->setDevice($deviceObject);
        $logObject->setInterfaceName($interface->getName()->get());
        $logObject->setMac($interface->getMac()->get());
        $logObject->setOctetsIn(intval($interface->getIn()->get()));
        $logObject->setOctetsOut(intval($interface->getOut()->get()));
        $logObject->setTime(time());

        return $logObject;
    }

    /**
     * @param Log  $logObject
     * @param bool $save
     * @return $this
     */
    protected function logData(Log $logObject, $save = true)
    {
        // Saving the data
        if ($save === true) {
            $this->logsModel->save($logObject);
        }

        return $this;
    }
}