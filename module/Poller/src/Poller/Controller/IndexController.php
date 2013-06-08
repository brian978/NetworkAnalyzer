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
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function snmpAction()
    {
        $poller = new SnmpPoller();
        $poller->setServiceLocator($this->serviceLocator);
        $poller->bandwidthPoll();
    }

    public function trafficAction()
    {
    }
}