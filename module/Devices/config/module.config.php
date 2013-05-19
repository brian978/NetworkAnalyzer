<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

return array(
    'router' => array(
        'routes' => array(
            'devices' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:lang]/devices',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Devices\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'lang' => 'en'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'status' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id[/:success]]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'id' => '0',
                                'success' => 'invalid'
                            )
                        )
                    ),
                    'module' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'id' => '0',
                            )
                        )
                    )
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Devices\Controller\Index' => 'Devices\Controller\IndexController',
            'Devices\Controller\Locations' => 'Devices\Controller\LocationsController',
            'Devices\Controller\Types' => 'Devices\Controller\TypesController',
            'Devices\Controller\Interfaces' => 'Devices\Controller\InterfacesController',
            'Devices\Controller\InterfaceTypes' => 'Devices\Controller\InterfaceTypesController',
        )
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        )
    )
);