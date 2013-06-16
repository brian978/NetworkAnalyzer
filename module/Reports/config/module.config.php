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
                            'route' => '/[:controller[/:action[/:success]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'success' => 'invalid'
                            )
                        )
                    ),
                    'module' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:dispatch]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'dispatch' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'convertUnit' => 'Reports\View\Helper\UnitConverter'
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
                        'allow' => array(
                            'generate_reports'
                        ),
                    ),
                )
            )
        ),
        // This section is used by the main controller to determine if the request should be allowed
        'controllers' => array(
            'Reports\Controller\Index' => array(
                'resource' => 'reports',
                'privileges' => array(
                    'interfacesTraffic' => 'generate_reports',
                    'devicesUptime' => 'generate_reports',
                    'devicesTraffic' => 'generate_reports',
                    'generate' => 'generate_reports',
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
                        'label' => 'Interfaces traffic',
                        'route' => 'reports/module',
                        'controller' => 'index',
                        'action' => 'interfacesTraffic',
                        'class' => 'icn_new_article',
                        'resource' => 'reports',
                        'privilege' => 'generate_reports'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Devices uptime',
                        'route' => 'reports/module',
                        'controller' => 'index',
                        'action' => 'devicesUptime',
                        'class' => 'icn_new_article',
                        'resource' => 'reports',
                        'privilege' => 'generate_reports'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Devices traffic',
                        'route' => 'reports/module',
                        'controller' => 'index',
                        'action' => 'devicesTraffic',
                        'class' => 'icn_new_article',
                        'resource' => 'reports',
                        'privilege' => 'generate_reports'
                    ),
                )
            ),
        )
    ),
);
