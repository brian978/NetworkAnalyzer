<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Library\Form\AbstractForm;
use SNMP\Manager\ObjectManager;
use SNMP\Manager\SessionManager;
use SNMP\Model\Session;
use Zend\Session\Container;

class IndexController extends AbstractController
{
    /**
     * @var int
     */
    protected $poolInterval = 3;

    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;

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
                'device' => array(
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
                )
            )
        );
    }

    public function monitorAction()
    {
        /** @var $sessionContainer \Zend\Session\Container */
        $this->sessionContainer = $this->serviceLocator->get('session');

        if (!isset($this->sessionContainer['devices'])) {
            $this->sessionContainer['devices'] = array();
        }

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

        // Manager objects
        $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
        $device        = $objectManager->getDevice();

        $this->calculateInterfaceBandwidth($deviceId, $device);

        return array(
            'device' => $device,
            'deviceInfo' => $deviceInfo,
        );
    }

    public function monitorAllAction()
    {
        $devices = array();

        /** @var $sessionContainer \Zend\Session\Container */
        $this->sessionContainer = $this->serviceLocator->get('session');

        if (!isset($this->sessionContainer['devices'])) {
            $this->sessionContainer['devices'] = array();
        }

        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $this->poolInterval);

        /** @var $model \Library\Model\AbstractDbModel */
        $model      = $this->getModel();
        $allDevices = $model->fetch();

        foreach ($allDevices as $deviceId => $deviceInfo) {
            $config = array(
                'version' => $deviceInfo->snmp_version,
                'hostname' => $deviceInfo->ip,
                'community' => $deviceInfo->snmp_community,
            );

            // Manager objects
            $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
            $device        = $objectManager->getDevice();

            $devices[$deviceId]['device'] = $device;

            // Calculating the bandwidth utilization
            if (!isset($this->sessionContainer['devices'][$deviceId])) {
                $this->sessionContainer['devices'][$deviceId] = array();
            }

            $this->calculateInterfaceBandwidth($deviceId, $device);
        }

        return array(
            'devices' => $devices,
        );
    }

    /**
     * @param $deviceId
     * @param $device
     */
    protected function calculateInterfaceBandwidth($deviceId, $device)
    {
        foreach ($device->getInterfaces() as $interface) {

            $speed = intval($interface->getSpeed()->get());

            if ($speed > 0) {

                $identifier = $interface->getIp()->__toString();
                $identifier .= $interface->getName()->__toString();

                // Calculating the bandwidth
                if (isset($this->sessionContainer['devices'][$deviceId][$identifier])) {

                    // Shortening the name
                    $interfaceData = $this->sessionContainer['devices'][$deviceId][$identifier];

                    // Calculating the differences
                    $diffInOctets  = intval($interface->getIn()->get()) - $interfaceData['in'];
                    $diffOutOctets = intval($interface->getOut()->get()) - $interfaceData['out'];
                    $diffTime      = time() - $interfaceData['time'];

                    // Calculating the bandwidth
                    $bytes         = (max($diffInOctets, $diffOutOctets) * 8);
                    $bandwidth     = $bytes / $diffTime;
                    $bandwidthType = 0;

                    while (floor($bandwidth) > 1024) {
                        $bandwidth = $bandwidth / 1024;
                        $bandwidthType++;
                    }

                    // Setting the data
                    $interface->setBandwidth(round($bandwidth, 2));
                    $interface->setBandwidthType($bandwidthType);
                }

                // Storing some data to allow us to calculate the bandwidth
                // Maybe move this to DB?
                $this->sessionContainer['devices'][$deviceId][$identifier] = array(
                    'out' => intval($interface->getOut()->get()),
                    'in' => intval($interface->getIn()->get()),
                    'time' => time()
                );
            }
        }
    }
}
