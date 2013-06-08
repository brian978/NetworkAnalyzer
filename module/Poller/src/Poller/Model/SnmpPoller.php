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
use Devices\Helper\DeviceData;
use Devices\Model\BandwidthLogs;
use SNMP\Helper\BandwidthCalculator;
use SNMP\Helper\InterfaceBandwidth;
use SNMP\Manager\ObjectManager;
use SNMP\Manager\SessionManager;
use SNMP\Model\Session;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class SnmpPoller
 *
 * @package Poller\Model
 */
class SnmpPoller extends AbstractModel
{
    public function bandwidthPoll($doLogging = true, $deviceId = 0)
    {
        $hydrator   = new ClassMethods();
        $deviceData = new DeviceData();
        $devices    = array();

        /** @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter            = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $logsModel          = new BandwidthLogs($adapter);
        $interfaceBandwidth = new InterfaceBandwidth(new BandwidthCalculator(), $logsModel);

        $interfaceBandwidth->doLogging = $doLogging;

        /** @var $model \Devices\Model\DevicesModel */
        $model = $this->serviceLocator->get('Devices\Model\DevicesModel');

        if (is_numeric($deviceId) && $deviceId > 0) {
            $allDevices = array(
                $deviceId => $model->getInfo($deviceId)
            );
        } else {
            $allDevices = $model->fetch();
        }

        foreach ($allDevices as $id => $deviceInfo) {

            $deviceData->setData($deviceInfo);

            /** @var $deviceEntity \Devices\Entity\Device */
            $deviceEntity = $hydrator->hydrate(
                $deviceData->extract(true),
                new Device()
            );

            // Manager objects
            $snmpSession   = new Session($this->serviceLocator, $deviceEntity);
            $objectManager = new ObjectManager(new SessionManager($snmpSession));
            $device        = $objectManager->getDevice();

            $device->setDeviceEntity($deviceEntity);
            $interfaceBandwidth->__invoke($deviceEntity, $device);

            $devices[$id]['device'] = $device;
        }

        return $devices;
    }
}