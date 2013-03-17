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
            'users' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/users[/:action][/:id]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        )
    )
);