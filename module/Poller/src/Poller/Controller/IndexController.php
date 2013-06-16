<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Poller\Controller;

use Poller\Model\SnmpPoller;
use Poller\Model\TrafficPoller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class IndexController extends AbstractActionController
{
    public function onDispatch(MvcEvent $event)
    {
        $this->layout('layout/poller.phtml');

        return parent::onDispatch($event);
    }

    public function snmpAction()
    {
        $poller = new SnmpPoller();
        $poller->setServiceLocator($this->serviceLocator);
        $poller->bandwidthPoll();

        return;
    }

    public function trafficAction()
    {
        set_time_limit(120);

        $poller = new TrafficPoller(new SnmpPoller());
        $poller->setServiceLocator($this->serviceLocator);
        $poller->tcpPoll(true);

        return;
    }
}
