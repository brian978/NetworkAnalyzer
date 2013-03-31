<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

return array(
    'view_manager' => array(
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/ui.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'error/denied' => __DIR__ . '/../view/error/denied.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'routeName' => 'UI\Library\View\Helpers\RouteName'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
        )
    ),

    // Permissions for each controller in the user interface
    'permissions' => array(
        'resources' => array(
            'dashboard',
            'devices',
            'users',
            'admin'
        ),
        'roles' => array(
            'admin' => array(
                'inherits' => null,
                'resources' => array(
                    'all' => array(
                        'allow' => null
                    )
                ),
            ),
            'guest' => array(
                'inherits' => null,
                'resources' => array(
                    'dashboard' => array(
                        'allow' => array(
                            'access'
                        )
                    )
                )
            ),
            'user' => array(
                'inherits' => 'guest',
                'resources' => array(
                    'users' => array(
                        'allow' => array(
                            'access',
                            'profile'
                        )
                    ),
                    'admin' => array(
                        'allow' => array(
                            'logout'
                        )
                    )
                )
            ),
            'technical' => array(
                'inherits' => 'user',
                'resources' => array(
                    'devices' => array(
                        'allow' => array(
                        )
                    ),
                    'users' => array(
                        'allow' => array(
                            'access',
                            'view_users'
                        )
                    )
                )
            )
        ),
        'controllers' => array(
            'Settings\Controller\Users' => array(
                'resource' => 'users',
                'privileges' => array(
                    'profile' => 'profile',
                    'addForm' => 'admin_users',
                    'editForm' => 'admin_users',
                    'processForm' => 'admin_users',
                    'list' => 'view_users'
                )
            )
        )
    ),

    // Navigation pages
    'navigation' => array(
        'default' => array(

            // Devices pages
            array(
                'type' => 'UI\Library\Navigation\Page\Mvc',
                'label' => 'Devices',
                'route' => 'index/module',
                'controller' => 'devices',
                'pages' => array(
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Add new device',
                        'route' => 'index/module',
                        'controller' => 'devices',
                        'action' => 'addForm',
                        'class' => 'icn_new_article',
                        'resource' => 'devices',
                        'privilege' => 'add_devices'
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'View devices',
                        'route' => 'index/module',
                        'controller' => 'devices',
                        'action' => 'list',
                        'class' => 'icn_categories',
                        'resource' => 'devices',
                        'privilege' => 'view_devices'
                    )
                )
            ),

            // Users pages
            array(
                'type' => 'UI\Library\Navigation\Page\Mvc',
                'label' => 'Users',
                'route' => 'settings/module',
                'controller' => 'users',
                'pages' => array(
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Add new user',
                        'route' => 'settings/module',
                        'controller' => 'users',
                        'action' => 'addForm',
                        'class' => 'icn_add_user',
                        'resource' => 'users',
                        'privilege' => 'add_users',
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'View users',
                        'route' => 'settings/module',
                        'controller' => 'users',
                        'action' => 'list',
                        'class' => 'icn_view_users',
                        'resource' => 'users',
                        'privilege' => 'view_users',
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Profile',
                        'route' => 'settings/module',
                        'controller' => 'users',
                        'action' => 'profile',
                        'class' => 'icn_profile',
                        'resource' => 'users',
                        'privilege' => 'profile'
                    )
                )
            ),

            // Admin pages
            array(
                'type' => 'UI\Library\Navigation\Page\Mvc',
                'label' => 'Admin',
                'route' => 'settings/module',
                'controller' => 'admin',
                'pages' => array(
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Options',
                        'route' => 'settings/module',
                        'controller' => 'options',
                        'class' => 'icn_settings',
                        'resource' => 'admin',
                        'privilege' => 'view_options',
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Security',
                        'route' => 'settings/module',
                        'controller' => 'security',
                        'class' => 'icn_security',
                        'resource' => 'admin',
                        'privilege' => 'view_security',
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Logout',
                        'route' => 'auth',
                        'action' => 'logout',
                        'class' => 'icn_jump_back',
                        'resource' => 'admin',
                        'privilege' => 'logout'
                    )
                )
            ),
        )
    ),
);