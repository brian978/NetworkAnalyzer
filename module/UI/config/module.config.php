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
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/ui.phtml',
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
            'navigation' => 'UI\Library\Navigation\Service\UINavigation'
        )
    ),

    // Navigation pages
    'navigation' => array(
        'uinav' => array(

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
                        'action' => 'addForm'
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'View devices',
                        'route' => 'index/module',
                        'controller' => 'devices',
                        'action' => 'list'
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
                        'action' => 'addForm'
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'View users',
                        'route' => 'settings/module',
                        'controller' => 'users',
                        'action' => 'list'
                    ),
                    array(
                        'type' => 'UI\Library\Navigation\Page\Mvc',
                        'label' => 'Profile',
                        'route' => 'settings/module',
                        'controller' => 'users',
                        'action' => 'profile'
                    )
                )
            ),
        )
    )
);