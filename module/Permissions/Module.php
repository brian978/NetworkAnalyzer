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
}