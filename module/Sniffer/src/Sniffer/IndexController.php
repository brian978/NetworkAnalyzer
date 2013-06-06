<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Sniffer;

use UI\Controller\AbstractUiController;

class IndexController extends AbstractUiController
{
    public function indexAction()
    {
        $model    = $this->getServiceLocator()->get('Devices\Model\DeviceModel');
        $deviceId = $this->getEvent()->getRouteMatch()->getParam('id');
    }
}