<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices;

use Devices\Model\DevicesModel;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Devices\Model\DevicesModel' => function ($serviceManager)
                {
                    $model = new DevicesModel($serviceManager->get('Zend\Db\Adapter\Adapter'));
                    return $model;
                }
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}