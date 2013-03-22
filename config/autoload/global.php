<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(

    // Dummy DB config
    'db' => array(
        'driver' => 'Pdo_Mysql',
        'database' => 'network_analyzer',
        'username' => 'root',
        'password' => 'root',
        'hostname' => 'localhost'
    ),

    // Available locales - used to determine and set translator locale
    'locales' => array(
        'ro' => 'ro_RO',
        'en' => 'en_US',
    ),

    'modules' => array(
        // Options for the SNMP module
        'snmp' => array(
            'version' => SNMP::VERSION_1,
            'hostname' => '127.0.0.1',
            'community' => 'SNMP::VERSION_1'
        )
    )
);
