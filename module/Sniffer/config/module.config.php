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
            'sniffer' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:lang]/sniffer',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Sniffer\Controller',
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
                            'route' => '/[:controller[/:action[/:device/:interface]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'device' => '[0-9]*',
                                'interface' => '[0-9]*',
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
            'Sniffer\Controller\Index' => 'Sniffer\Controller\IndexController',
        )
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        )
    )
);
