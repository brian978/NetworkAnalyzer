<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices;

use Library\Module as MainModule;

use Devices\Model\DevicesModel;
use Devices\Model\InterfaceTypesModel;
use Devices\Model\InterfacesModel;
use Devices\Model\LocationsModel;
use Devices\Model\TypesModel;

class Module extends MainModule
{
    /**
     * @var string
     */
    protected $moduleDir = __DIR__;

    /**
     * @var string
     */
    protected $moduleNamespace = __NAMESPACE__;

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Devices\Model\DevicesModel' => function ($serviceManager) {
                    return new DevicesModel($serviceManager->get(
                        'Zend\Db\Adapter\Adapter'
                    ));
                },
                'Devices\Model\LocationsModel' => function ($serviceManager) {
                    return new LocationsModel($serviceManager->get(
                        'Zend\Db\Adapter\Adapter'
                    ));
                },
                'Devices\Model\TypesModel' => function ($serviceManager) {
                    return new TypesModel($serviceManager->get(
                        'Zend\Db\Adapter\Adapter'
                    ));
                },
                'Devices\Model\InterfacesModel' => function ($serviceManager) {
                    return new InterfacesModel($serviceManager->get(
                        'Zend\Db\Adapter\Adapter'
                    ));
                },
                'Devices\Model\InterfaceTypesModel' => function (
                    $serviceManager
                ) {
                    return new InterfaceTypesModel($serviceManager->get(
                        'Zend\Db\Adapter\Adapter'
                    ));
                },
            )
        );
    }
}