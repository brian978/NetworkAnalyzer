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
use Devices\Model\LocationsModel;
use Library\Form\Factory;
use Zend\Form\FormElementManager;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Devices\Model\DevicesModel' => function ($serviceManager)
                {
                    return new DevicesModel($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'Devices\Model\LocationsModel' => function ($serviceManager)
                {
                    return new LocationsModel($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'TranslatableFormFactory' => function ($serviceManager)
                {
                    $formElementManager = new FormElementManager();
                    $factory            = new Factory($formElementManager);

                    // Injecting the serviceLocator into the formElementManager
                    $formElementManager->setServiceLocator($serviceManager);

                    return $factory;
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