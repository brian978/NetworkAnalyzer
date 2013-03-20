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
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/[:lang[/:action]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dashboard\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Dashboard\Controller\Index' => 'Dashboard\Controller\IndexController'
        )
    )
);