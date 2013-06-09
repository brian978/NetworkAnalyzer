<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Devices\Helper\DeviceData;
use Devices\Model\BandwidthLogs;
use Library\Form\AbstractForm;
use Poller\Model\SnmpPoller;
use Poller\Model\TrafficPoller;
use SNMP\Manager\Objects\Device\Device as SnmpDevice;
use Zend\Session\Container;

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

    public function monitorAction()
    {
        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $this->pollInterval);

        /** @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter   = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $logsModel = new BandwidthLogs($adapter);

        $poller = new SnmpPoller();
        $poller->setServiceLocator($this->serviceLocator);

        $deviceId = $this->getEvent()->getRouteMatch()->getParam('id');
        $devices  = $poller->bandwidthPoll(true, $deviceId);
        $device   = $devices[$deviceId]['device'];

        $trafficPoller  = new TrafficPoller($poller);
        $trafficFeature = $trafficPoller->isServerAvailable($device->getDeviceEntity()->getInterface()->getIp());

        return array(
            'device' => $device,
            'trafficFeature' => $trafficFeature,
            'stats' => $this->buildStats($device, $logsModel)
        );
    }

    /**
     * @param SnmpDevice    $device
     * @param BandwidthLogs $logsModel
     * @return array
     */
    protected function buildStats(SnmpDevice $device, BandwidthLogs $logsModel)
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
