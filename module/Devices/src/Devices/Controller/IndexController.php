<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Devices\Entity\Device;
use Devices\Helper\DeviceData;
use Devices\Model\LogsModel;
use Library\Form\AbstractForm;
use SNMP\Helper\BandwidthCalculator;
use SNMP\Helper\InterfaceBandwidth;
use SNMP\Manager\ObjectManager;
use SNMP\Manager\Objects\Device\Device as SnmpDevice;
use SNMP\Manager\SessionManager;
use SNMP\Model\Session;
use Zend\Session\Container;
use Zend\Stdlib\Hydrator\ClassMethods;

class IndexController extends AbstractController
{
    /**
     * @var int
     */
    protected $pollInterval = 1;

    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '\Devices\Form\DevicesFrom',
        'object' => '\Devices\Entity\Device',
        'model' => 'Devices\Model\DevicesModel',
    );

    /**
     *
     * @param \Library\Form\AbstractForm $form
     * @param \ArrayAccess               $object
     */
    protected function populateEditData(
        AbstractForm $form,
        \ArrayAccess $object
    ) {
        // Arranging the data properly so that the form would be auto-populated
        $form->setData(
            array(
                'device' => $this->extractDeviceData($object)
            )
        );
    }

    /**
     * @param \ArrayAccess $object
     * @param bool         $createObjects
     * @return array
     */
    protected function extractDeviceData(\ArrayAccess $object, $createObjects = false)
    {
        $deviceData = new DeviceData($object);

        return $deviceData->extract($createObjects);
    }

    public function snifferAction()
    {
//        $command = 'java -jar ' . getcwd() . '/proxy/dispatcher.jar -mode client -command "tcpdump -i eth0 -nqt -c 20"';
//        $output  = shell_exec($command);
//        $output = explode(chr(13) . chr(10), $output);
//
//        var_dump($command);

        $response = $this->getResponse();
        $response->setStatusCode(200);
        return $response;
    }

    public function monitorAction()
    {
        $logsModel          = new LogsModel($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));
        $interfaceBandwidth = new InterfaceBandwidth(new BandwidthCalculator(), $logsModel);

        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $this->pollInterval);

        /** @var $model \Library\Model\AbstractDbModel */
        $model      = $this->getModel();
        $deviceId   = $this->getEvent()->getRouteMatch()->getParam('id');
        $deviceInfo = $model->getInfo($deviceId);

        $config = array(
            'version' => $deviceInfo->snmp_version,
            'hostname' => $deviceInfo->ip,
            'community' => $deviceInfo->snmp_community,
        );

        $hydrator = new ClassMethods();

        /** @var $deviceObject Device */
        $deviceObject = $hydrator->hydrate(
            $this->extractDeviceData($deviceInfo, true),
            new Device()
        );

        // Manager objects
        $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
        $device        = $objectManager->getDevice();

        $device->setDeviceEntity($deviceObject);

        $interfaceBandwidth($deviceObject, $device);

        return array(
            'device' => $device,
            'deviceInfo' => $deviceInfo,
            'stats' => $this->buildStats($device, $logsModel)
        );
    }

    /**
     * @param SnmpDevice                          $device
     * @param LogsModel                           $logsModel
     * @return array
     */
    protected function buildStats(SnmpDevice $device, LogsModel $logsModel)
    {
        $result = array();
        $min    = new \stdClass();
        $avg    = new \stdClass();
        $max    = new \stdClass();

        // Adding the interfaces to the result
        foreach ($device->getInterfaces() as $interface) {
            $interfaceName = $interface->getName()->get();

            $result[$interfaceName] = array(
                'min' => $min,
                'avg' => $avg,
                'max' => $max,
            );

            $inOut      = new \stdClass();
            $inOut->in  = 0;
            $inOut->out = 0;

            $min->{$interfaceName} = $inOut;
            $avg->{$interfaceName} = clone $inOut;
            $max->{$interfaceName} = clone $inOut;
        }

        // Getting some statistics for the last 60 seconds
        $stats = $logsModel->getLastSeconds(60, $device->getDeviceEntity()->getId());

        foreach ($stats as $log) {
            $interfaceName = $log['interface_name'];

            if (isset($result[$interfaceName])) {
                $avg->{$interfaceName}->in  = round(($avg->{$interfaceName}->in + $log['bandwidth_in']) / 2, 2);
                $avg->{$interfaceName}->out = round(($avg->{$interfaceName}->out + $log['bandwidth_out']) / 2, 2);

                $max->{$interfaceName}->in  = floatval(max($max->{$interfaceName}->in, $log['bandwidth_in']));
                $max->{$interfaceName}->out = floatval(max($max->{$interfaceName}->out, $log['bandwidth_out']));
            }
        }

        return $result;
    }
}
