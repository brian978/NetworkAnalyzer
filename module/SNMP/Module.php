<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP;

use Library\Module as MainModule;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

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

    public function onBootstrap(MvcEvent $e)
    {
        /** @var $app \Zend\Mvc\Application */
        $app            = $e->getApplication();
        $serviceManager = $app->getServiceManager();

        // Creating a session
        $serviceManager->setService('session', new Container());
    }
}
