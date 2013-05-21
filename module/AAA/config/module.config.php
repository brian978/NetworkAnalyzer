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
            'auth' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:lang]/auth[/:action]',
                    'defaults' => array(
                        'controller' => 'AAA\Controller\Index',
                        'action' => 'index',
                        'lang' => 'en'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'AAA\Controller\Index' => 'AAA\Controller\IndexController'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'authorization' => 'AAA\Services\AuthorizationFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);