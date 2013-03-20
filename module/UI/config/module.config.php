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
    )
);