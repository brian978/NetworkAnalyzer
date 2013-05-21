<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users;

use Library\Module as MainModule;
use Users\Model\Roles;
use Users\Model\Users;

class Module extends MainModule
{
    /**
     * @var string
     */
    protected $moduleDir = __DIR__;

    /**
     * @var string
     */
    protected $moduleNamespace = __NAMESPACE__;

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Users\Model\UsersModel' => function ($serviceManager) {
                    return new Users($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'Users\Model\RolesModel' => function ($serviceManager) {
                    return new Roles($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
            )
        );
    }
}