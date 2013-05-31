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
use Devices\Entity\Iface;
use Devices\Entity\Log;
use Devices\Entity\Type;
use Devices\Model\LogsModel;
use Library\Form\AbstractForm;
use SNMP\Manager\ObjectManager;
use SNMP\Manager\SessionManager;
use SNMP\Model\Session;
use Zend\Session\Container;
use Zend\Stdlib\Hydrator\ClassMethods;

class IndexController extends AbstractController
{
    /**
     * @var int
     */
    protected $poolInterval = 3;

    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs
        = array(
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
        $deviceData = array(
            'id' => $object->id,
            'name' => $object->name,
            'snmpVersion' => $object->snmp_version,
            'snmpCommunity' => $object->snmp_community,
            'type' => array(
                'id' => $object->type_id
            ),
            'interface' => array(
                'ip' => $object->ip,
                'type' => array(
                    'id' => $object->interface_type_id
                )
            )
        );

        if ($createObjects === true) {

            $hydrator = new ClassMethods();

            $deviceData['type']              = $hydrator->hydrate($deviceData['type'], new Type());
            $deviceData['interface']['type'] = $hydrator->hydrate($deviceData['interface']['type'], new Type());
            $deviceData['interface']         = $hydrator->hydrate($deviceData['interface'], new Iface());
        }

        return $deviceData;
    }

    public function monitorAction()
    {
        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $this->poolInterval);

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

        $this->calculateInterfaceBandwidth($deviceObject, $device);

        return array(
            'device' => $device,
            'deviceInfo' => $deviceInfo,
        );
    }

    public function monitorAllAction()
    {
        $devices = array();

        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $this->poolInterval);

        /** @var $model \Library\Model\AbstractDbModel */
        $model      = $this->getModel();
        $allDevices = $model->fetch();

        $hydrator = new ClassMethods();

        foreach ($allDevices as $deviceId => $deviceInfo) {
            $config = array(
                'version' => $deviceInfo->snmp_version,
                'hostname' => $deviceInfo->ip,
                'community' => $deviceInfo->snmp_community,
            );

            /** @var $deviceObject Device */
            $deviceObject = $hydrator->hydrate(
                $this->extractDeviceData($deviceInfo, true),
                new Device()
            );

            // Manager objects
            $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
            $device        = $objectManager->getDevice();

            $devices[$deviceId]['device'] = $device;

            // Calculating the bandwidth and inserting logs
            $this->calculateInterfaceBandwidth($deviceObject, $device);
        }

        return array(
            'devices' => $devices,
        );
    }

    /**
     * @param Device $deviceObject
     * @param        $device
     */
    protected function calculateInterfaceBandwidth(Device $deviceObject, $device)
    {
        $deviceId = $deviceObject->getId();

        foreach ($device->getInterfaces() as $interface) {

            $speed = intval($interface->getSpeed()->get());

            if ($speed > 0) {

                // The logs model is required to retrieve the last data about an interface
                $logsModel = new LogsModel($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));

                /**
                 * -------------------------------------
                 * LOGGING
                 * -------------------------------------
                 */
                $executeSave = true;

                $logObject = new Log();
                $logObject->setUptime($device->getUptime());
                $logObject->setOidIndex($interface->getOidIndex());
                $logObject->setDevice($deviceObject);
                $logObject->setInterfaceName($interface->getName()->get());
                $logObject->setMac($interface->getMac()->get());
                $logObject->setOctetsIn(intval($interface->getIn()->get()));
                $logObject->setOctetsOut(intval($interface->getOut()->get()));
                $logObject->setTime(time());

                /**
                 * -------------------------------------
                 * GETTING DATA AND CALCULATING
                 * -------------------------------------
                 */
                $interfaceData = $logsModel->getLastEntries($interface->getOidIndex(), $deviceId);

                // We need 2 logs to calculate properly
                if (!empty($interfaceData) && count($interfaceData) == 2) {

                    $last = array_shift($interfaceData);
                    $prev = array_shift($interfaceData);

                    // Queries with the same timestamp must not be saved
                    if (strpos($last['uptime'], $prev['uptime']) === 0) {
                        $executeSave = false;
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
                    $bandwidthIn     = $this->calculateBandwidth($diffInOctets, $diffTime);
                    $bandwidthInType = 0;

                    while (floor($bandwidthIn) > 1024 && $bandwidthInType < 2) {
                        $bandwidthIn = $bandwidthIn / 1024;
                        $bandwidthInType++;
                    }

                    // Setting the data
                    $interface->setBandwidthIn(round($bandwidthIn, 2));
                    $interface->setBandwidthInType($bandwidthInType);

                    /**
                     * ------------------
                     * OUT BANDWIDTH
                     * ------------------
                     */
                    $bandwidthOut     = $this->calculateBandwidth($diffOutOctets, $diffTime);
                    $bandwidthOutType = 0;

                    while (floor($bandwidthOut) > 1024 && $bandwidthOutType < 2) {
                        $bandwidthOut = $bandwidthOut / 1024;
                        $bandwidthOutType++;
                    }

                    // Setting the data
                    $interface->setBandwidthOut(round($bandwidthOut, 2));
                    $interface->setBandwidthOutType($bandwidthOutType);
                }

                // Saving the data
                if ($executeSave === true) {
                    $logsModel->save($logObject);
                }
            }
        }
    }

    /**
     * @param $octetsDiff
     * @param $timeDiff
     * @return float
     */
    protected function calculateBandwidth($octetsDiff, $timeDiff)
    {
        // To avoid division by 0
        if ($timeDiff == 0) {
            $timeDiff = 1;
        }

        $bandwidth = ($octetsDiff * 8) / ($timeDiff);

        return $bandwidth;
    }
}
