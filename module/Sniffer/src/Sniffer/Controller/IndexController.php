<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Sniffer\Controller;

use Library\Object\ArrayObject\Helper\ObjectConverter;
use Poller\Model\SnmpPoller;
use Poller\Model\TrafficPoller;
use Sniffer\Model\TrafficLogs;
use Sniffer\Object\Traffic\Connection;
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

            /** @var $adapter \Zend\Db\Adapter\Adapter */
            $adapter   = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
            $logsModel = new TrafficLogs($adapter);

            $logsModel->addWhere('iface_name', $interfaceName);
            $dbConnections = $logsModel->getLastSeconds(60, $deviceId);

            if (empty($dbConnections)) {
                $poller = new TrafficPoller(new SnmpPoller());
                $poller->setServiceLocator($this->serviceLocator);
                $connections = $poller->tcpPoll(true, $deviceId, $interfaceName);
            } else {
                foreach ($dbConnections as $connection) {

                    $objectConverter          = new ObjectConverter($connection, new Connection());
                    $connections[$deviceId][] = $objectConverter->convert();
                }
            }
        }

        return array(
            'noInterface' => $noInterface,
            'connections' => $connections
        );
    }
}
