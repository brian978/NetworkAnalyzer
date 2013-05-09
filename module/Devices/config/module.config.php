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
                    'module' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            )
                        )
                    )
                )
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Devices\Controller\Index' => 'Devices\Controller\IndexController'
        )
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        )
    )
);