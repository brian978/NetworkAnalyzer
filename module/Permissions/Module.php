<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Permissions;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        // Events
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            array(
                $this,
                'injectLayoutVariables'
            )
        );
    }

    public function injectLayoutVariables(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $layoutModel    = $e->getViewModel();

        $layoutModel->setVariable('acl', $serviceManager->get('acl'));
        $layoutModel->setVariable('userAuth', $serviceManager->get('authorization'));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}