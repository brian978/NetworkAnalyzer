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

    'permissions' => array(
        'resources' => array(
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
                )
            ),
            'user' => array(
                'inherits' => 'guest',
                'resources' => array(
                    'users' => array(
                        'allow' => array(
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
                            'view_users'
                        )
                    )
                )
            )
        )
    )
);