<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Sniffer\Controller;

use UI\Controller\AbstractUiController;

class IndexController extends AbstractUiController
{
    public function indexAction()
    {
        $noInterface = false;

        $model         = $this->getServiceLocator()->get('Devices\Model\DevicesModel');
        $deviceId      = $this->getEvent()->getRouteMatch()->getParam('device');
        $interfaceName = $this->getEvent()->getRouteMatch()->getParam('interface');

        if ($interfaceName === null) {
            $noInterface = true;
        }

        return array(
            'noInterface' => $noInterface,
        );
    }
}