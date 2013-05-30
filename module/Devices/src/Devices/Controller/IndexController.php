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
        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', 3);

        /** @var $model \Library\Model\AbstractDbModel */
        $model      = $this->getModel();
        $deviceInfo = $model->getInfo(
            $this->getEvent()->getRouteMatch()->getParam('id')
        );

        $config = array(
            'version' => $deviceInfo->snmp_version,
            'hostname' => $deviceInfo->ip,
            'community' => $deviceInfo->snmp_community,
        );

        // Manager objects
        $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
        $device        = $objectManager->getDevice();

        return array(
            'device' => $device,
            'deviceInfo' => $deviceInfo,
        );
    }

    public function monitorAllAction()
    {
        $poolInterval = 1;
        $devices      = array();

        /** @var $sessionContainer \Zend\Session\Container */
        $sessionContainer = $this->serviceLocator->get('session');

        if (!isset($sessionContainer['devices'])) {
            $sessionContainer['devices'] = array();
        }

        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $poolInterval);

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
            if (!isset($sessionContainer['devices'][$deviceId])) {
                $sessionContainer['devices'][$deviceId] = array();
            }

            foreach ($device->getInterfaces() as $interface) {

                $speed = intval($interface->getSpeed()->get());

                if ($speed > 0) {

                    $identifier = $interface->getIp()->__toString();
                    $identifier .= $interface->getName()->__toString();

                    // Calculating the bandwidth
                    if (isset($sessionContainer['devices'][$deviceId][$identifier])) {

                        // Shortening the name
                        $interfaceData = $sessionContainer['devices'][$deviceId][$identifier];

                        $diffInOctets  = intval($interface->getIn()->get()) - $interfaceData['in'];
                        $diffOutOctets = intval($interface->getOut()->get()) - $interfaceData['out'];
                        $diffTime      = time() - $interfaceData['time'];

                        $bytes         = (max($diffInOctets, $diffOutOctets) * 8);
                        $bandwidth     = $bytes / $diffTime;
                        $bandwidthType = 0;

                        while (floor($bandwidth) > 1024) {
                            $bandwidth = $bandwidth / 1024;
                            $bandwidthType++;
                        }

                        $interface->setBandwidth(round($bandwidth, 2));
                        $interface->setBandwidthType($bandwidthType);
                    }

                    // Storing some data to allow us to calculate the bandwidth
                    // Maybe move this to DB?
                    $sessionContainer['devices'][$deviceId][$identifier] = array(
                        'out' => intval($interface->getOut()->get()),
                        'in' => intval($interface->getIn()->get()),
                        'time' => time()
                    );
                }
            }
        }

        return array(
            'devices' => $devices,
        );
    }
}
