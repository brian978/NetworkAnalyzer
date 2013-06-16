<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Controller;

use Poller\Model\SnmpPoller;
use Poller\Model\TrafficPoller;
use UI\Controller\AbstractUiController;

class IndexController extends AbstractUiController
{
    /**
     * @var int
     */
    protected $pollInterval = 60;

    public function indexAction()
    {
        // Setting a refresh interval for the page when accessed through the web
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', $this->pollInterval);

        $poller = new SnmpPoller();
        $poller->setServiceLocator($this->serviceLocator);

        $devices       = $poller->bandwidthPoll();
        $trafficPoller = new TrafficPoller($poller);

        foreach ($devices as $deviceId => $device) {
            $deviceIp                             = $device['device']->getDeviceEntity()->getInterface()->getIp();
            $devices[$deviceId]['trafficFeature'] = $trafficPoller->isServerAvailable($deviceIp);
        }

        return array(
            'devices' => $devices,
        );
    }
}
