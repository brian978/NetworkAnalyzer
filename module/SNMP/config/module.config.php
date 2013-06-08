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
        'routes' => array()
    ),
    'controllers' => array(
        'invokables' => array()
    ),
    'view_helpers' => array(
        'invokables' => array(
            'bandwidth' => 'SNMP\View\Helper\BandwidthCalculator'
        )
    )
);
