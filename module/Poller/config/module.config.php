<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'poll-snmp-devices' => array(
                    'options' => array(
                        'route' => 'poll snmp devices',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Poller\Controller',
                            'controller' => 'index',
                            'action' => 'snmp',
                            'lang' => 'en'
                        )
                    )
                ),
                'poll-trafic' => array(
                    'options' => array(
                        'route' => 'poll traffic',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Poller\Controller',
                            'controller' => 'index',
                            'action' => 'traffic',
                            'lang' => 'en'
                        )
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Poller\Controller\Index' => 'Poller\Controller\IndexController',
        )
    ),
);