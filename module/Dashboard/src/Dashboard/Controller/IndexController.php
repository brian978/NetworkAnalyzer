<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Controller;

use Devices\Entity\Device;
use Devices\Helper\DeviceData;
use Devices\Model\LogsModel;
use SNMP\Helper\BandwidthCalculator;
use SNMP\Helper\InterfaceBandwidth;
use SNMP\Manager\ObjectManager;
use SNMP\Manager\SessionManager;
use SNMP\Model\Session;
use UI\Controller\AbstractUiController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Stdlib\Hydrator\ClassMethods;

class IndexController extends AbstractUiController
{
    /**
     * @var int
     */
    protected $pollInterval = 3;

    public function indexAction()
    {
        $requestType = 'http';

        $deviceData = new DeviceData();
        $devices    = array();

        $logsModel          = new LogsModel($this->serviceLocator->get('Zend\Db\Adapter\Adapter'));
        $interfaceBandwidth = new InterfaceBandwidth(new BandwidthCalculator(), $logsModel);

        // Setting a refresh interval for the page when accessed through the web
        if ($this->getRequest() instanceof ConsoleRequest == false) {
            /** @var  $headers \Zend\Http\Headers */
            $headers = $this->getResponse()->getHeaders();
            $headers->addHeaderLine('Refresh', $this->pollInterval);
        } else {
            $requestType = 'console';
        }

        /** @var $model \Devices\Model\DevicesModel */
        $model      = $this->serviceLocator->get('Devices\Model\DevicesModel');
        $allDevices = $model->fetch();

        $hydrator = new ClassMethods();

        foreach ($allDevices as $deviceId => $deviceInfo) {
            $config = array(
                'version' => $deviceInfo->snmp_version,
                'hostname' => $deviceInfo->ip,
                'community' => $deviceInfo->snmp_community,
            );

            $deviceData->setData($deviceInfo);

            /** @var $deviceObject Device */
            $deviceObject = $hydrator->hydrate(
                $deviceData->extract(true),
                new Device()
            );

            // Manager objects
            $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
            $device        = $objectManager->getDevice();

            $device->setDeviceEntity($deviceObject);

            $devices[$deviceId]['device'] = $device;

            // Calculating the bandwidth and inserting logs
            $interfaceBandwidth($deviceObject, $device);
        }

        if ($requestType == 'http') {
            return array(
                'devices' => $devices,
            );
        } else {
            return 'Poll done';
        }
    }
}
