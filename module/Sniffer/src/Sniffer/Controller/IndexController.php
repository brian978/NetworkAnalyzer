<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Sniffer\Controller;

use Poller\Model\SnmpPoller;
use Poller\Model\TrafficPoller;
use UI\Controller\AbstractUiController;

class IndexController extends AbstractUiController
{
    public function indexAction()
    {
        $noInterface = false;
        $connections = array();

        $deviceId      = $this->getEvent()->getRouteMatch()->getParam('device');
        $interfaceName = $this->getEvent()->getRouteMatch()->getParam('interface');

        if ($interfaceName === null) {
            $noInterface = true;
        } else {

            $poller = new TrafficPoller(new SnmpPoller());
            $poller->setServiceLocator($this->serviceLocator);
            $connections = $poller->tcpPoll(false, $deviceId, $interfaceName);
        }

        return array(
            'noInterface' => $noInterface,
            'connections' => $connections
        );
    }
}
