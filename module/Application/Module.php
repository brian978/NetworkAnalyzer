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

class Module
{
//    public function init(ModuleManager $moduleManager)
//    {
//        // Not used => unset (also avoid code inspection errors)
//        unset($moduleManager);
//
//        // Setting up the environment
//        $this->setEnv();
//    }

    protected function setEnv()
    {
        $env = getenv('APPLICATION_ENV');

        if($env !== false && ($env === 'development' || $env === 'staging'))
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
