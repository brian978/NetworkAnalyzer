<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Library\Module as MainModule;

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

    public function init()
    {
        // Setting up the environment
        $this->setEnv();
    }

    protected function setEnv()
    {
        $env = getenv('APPLICATION_ENV');

        if ($env !== false && ($env === 'development' || $env === 'staging'))
        {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }

    public function onBootstrap(MvcEvent $e)
    {
        // Used for child routes
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($e->getApplication()->getEventManager());
    }
}
