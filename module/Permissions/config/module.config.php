<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'acl' => '\Permissions\Services\InitializeAcl'
        )
    ),
    // Permissions for each controller in the user interface
    'permissions' => array(
        'resources' => array(),
        'roles' => array(),
        'controllers' => array()
    ),
);
