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
            'reports' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:lang]/reports',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Reports\Controller',
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
            'Reports\Controller\Index' => 'Reports\Controller\IndexController',
        )
    ),
    'permissions' => array(
        'resources' => array(
            'reports',
        ),
        // These are the types of users
        'roles' => array(
            'technical' => array(
                'inherits' => 'user',
                'resources' => array(
                    'reports' => array(
                        'allow' => null,
                    ),
                )
            )
        ),
        // This section is used by the main controller to determine if the request should be allowed
        'controllers' => array(
            'Reports\Controller\Reports' => array(
                'resource' => 'reports',
                'privileges' => array(
                    'trafficReport' => 'generate_reports',
                )
            ),
        )
    ),
    // Navigation pages
    'navigation' => array(
        'default' => array(
            array(
                'type' => 'Library\Navigation\Page\Mvc',
                'label' => 'Reports',
                'route' => 'reports/module',
                'controller' => 'index',
                'pages' => array(
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Interface traffic report',
                        'route' => 'reports/module',
                        'controller' => 'index',
                        'action' => 'interfaceTraffic',
                        'class' => 'icn_new_article',
                        'resource' => 'reports',
                        'privilege' => 'generate_reports'
                    ),
                )
            ),
        )
    ),
);
