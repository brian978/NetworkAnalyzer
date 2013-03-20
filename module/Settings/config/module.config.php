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
            'settings' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:lang]/settings',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Settings\Controller',
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
                            ),
                            'defaults' => array(
                                'lang' => 'en'
                            )
                        )
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Settings\Controller\Index' => 'Settings\Controller\IndexController',
            'Settings\Controller\Users' => 'Settings\Controller\UsersController'
        )
    )
);