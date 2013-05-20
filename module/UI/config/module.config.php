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
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
        )
    ),
    // Permissions for each controller in the user interface
    'permissions' => array(

        // These are basically the sections of the site
        'resources' => array(
            'dashboard',
            'devices',
            'users',
            'admin'
        ),
        // These are the types of users
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

                        // Empty array to allow all the permission
                        'allow' => array()
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
        // This section is used by the main controller to determine if the request should be allowed
        'controllers' => array(
            'Dashboard\Controller\Devices' => array(
                'resource' => 'devices',
                'privileges' => array(
                    'index' => 'view_devices',
                    'addForm' => 'admin_devices',
                    'editForm' => 'editForm',
                    'list' => 'view_devices'
                )
            ),
            'Settings\Controller\Index' => array(
                'resource' => 'admin',
                'privileges' => array(
                    'all' => 'access'
                )
            ),
            'Settings\Controller\Options' => array(
                'resource' => 'admin',
                'privileges' => array(
                    'all' => 'access'
                )
            ),
            'Settings\Controller\Security' => array(
                'resource' => 'admin',
                'privileges' => array(
                    'all' => 'access'
                )
            ),
            'Settings\Controller\Users' => array(
                'resource' => 'users',
                'privileges' => array(
                    'profile' => 'profile',
                    'addForm' => 'admin_users',
                    'editForm' => 'addForm',
                    // Inherit from addForm
                    'processForm' => 'addForm',
                    // Inherit from addForm
                    'list' => 'view_users'
                )
            ),
        )
    ),
    // Navigation pages
    'navigation' => array(
        'default' => array(

            // Devices pages
            array(
                'type' => 'Library\Navigation\Page\Mvc',
                'label' => 'Devices',
                'route' => 'devices/module',
                'controller' => 'index',
                'pages' => array(

                    // Devices
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Add new device',
                        'route' => 'devices/module',
                        'controller' => 'index',
                        'action' => 'addForm',
                        'class' => 'icn_new_article',
                        'resource' => 'devices',
                        'privilege' => 'add_devices'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'View devices',
                        'route' => 'devices/module',
                        'controller' => 'index',
                        'action' => 'list',
                        'class' => 'icn_categories',
                        'resource' => 'devices',
                        'privilege' => 'view_devices'
                    ),
                    // Locations
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Add new location',
                        'route' => 'devices/module',
                        'controller' => 'locations',
                        'action' => 'addForm',
                        'class' => 'icn_new_article',
                        'resource' => 'devices',
                        'privilege' => 'add_locations'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'View locations',
                        'route' => 'devices/module',
                        'controller' => 'locations',
                        'action' => 'list',
                        'class' => 'icn_categories',
                        'resource' => 'devices',
                        'privilege' => 'view_locations'
                    ),
                    // Types
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Add new type',
                        'route' => 'devices/module',
                        'controller' => 'types',
                        'action' => 'addForm',
                        'class' => 'icn_new_article',
                        'resource' => 'devices',
                        'privilege' => 'add_types'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'View types',
                        'route' => 'devices/module',
                        'controller' => 'types',
                        'action' => 'list',
                        'class' => 'icn_categories',
                        'resource' => 'devices',
                        'privilege' => 'view_types'
                    ),
                    // Interfaces
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Add new interface',
                        'route' => 'devices/module',
                        'controller' => 'interfaces',
                        'action' => 'addForm',
                        'class' => 'icn_new_article',
                        'resource' => 'devices',
                        'privilege' => 'add_interfaces'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'View interfaces',
                        'route' => 'devices/module',
                        'controller' => 'interfaces',
                        'action' => 'list',
                        'class' => 'icn_categories',
                        'resource' => 'devices',
                        'privilege' => 'view_interfaces'
                    ),
                    // Interface types
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Add new interface type',
                        'route' => 'devices/module',
                        'controller' => 'interfaceTypes',
                        'action' => 'addForm',
                        'class' => 'icn_new_article',
                        'resource' => 'devices',
                        'privilege' => 'add_interface_types'
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'View the interface types',
                        'route' => 'devices/module',
                        'controller' => 'interfaceTypes',
                        'action' => 'list',
                        'class' => 'icn_categories',
                        'resource' => 'devices',
                        'privilege' => 'view_interface_types'
                    ),
                )
            ),
            // Users pages
            array(
                'type' => 'Library\Navigation\Page\Mvc',
                'label' => 'Users',
                'route' => 'users/module',
                'controller' => 'users',
                'pages' => array(
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Add new user',
                        'route' => 'users/module',
                        'controller' => 'index',
                        'action' => 'addForm',
                        'class' => 'icn_add_user',
                        'resource' => 'users',
                        'privilege' => 'add_users',
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'View users',
                        'route' => 'users/module',
                        'controller' => 'index',
                        'action' => 'list',
                        'class' => 'icn_view_users',
                        'resource' => 'users',
                        'privilege' => 'view_users',
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Profile',
                        'route' => 'users/module',
                        'controller' => 'index',
                        'action' => 'profile',
                        'class' => 'icn_profile',
                        'resource' => 'users',
                        'privilege' => 'profile'
                    )
                )
            ),
            // Admin pages
            array(
                'type' => 'Library\Navigation\Page\Mvc',
                'label' => 'Admin',
                'route' => 'settings/module',
                'controller' => 'admin',
                'pages' => array(
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Options',
                        'route' => 'settings/module',
                        'controller' => 'options',
                        'class' => 'icn_settings',
                        'resource' => 'admin',
                        'privilege' => 'view_options',
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
                        'label' => 'Security',
                        'route' => 'settings/module',
                        'controller' => 'security',
                        'class' => 'icn_security',
                        'resource' => 'admin',
                        'privilege' => 'view_security',
                    ),
                    array(
                        'type' => 'Library\Navigation\Page\Mvc',
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