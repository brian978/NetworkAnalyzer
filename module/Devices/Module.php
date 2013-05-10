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
use Devices\Model\InterfaceTypesModel;
use Devices\Model\InterfacesModel;
use Devices\Model\LocationsModel;
use Devices\Model\TypesModel;

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
                'Devices\Model\TypesModel' => function ($serviceManager)
                {
                    return new TypesModel($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'Devices\Model\InterfacesModel' => function ($serviceManager)
                {
                    return new InterfacesModel($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'Devices\Model\InterfaceTypesModel' => function ($serviceManager)
                {
                    return new InterfaceTypesModel($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
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