<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace UI;

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

    /**
     *
     * @var \Zend\Mvc\ApplicationInterface
     */
    protected $application;

    /**
     * @var \Zend\EventManager\EventManager
     */
    protected $eventManager;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    public function onBootstrap(MvcEvent $e)
    {
        $this->application    = $e->getApplication();
        $this->eventManager   = $this->application->getEventManager();
        $this->serviceManager = $this->application->getServiceManager();

        // Events
        $this->eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            array(
                $this,
                'setLocale'
            )
        , 100);
    }

    public function setLocale(MvcEvent $e)
    {
        $match = $e->getRouteMatch();
        $lang  = $match->getParam('lang');

        if (is_string($lang))
        {
            /** @var $config \Zend\Config\Config */
            $config = $this->serviceManager->get('Config');

            /** @var $translator \Zend\I18n\Translator\Translator */
            $translator = $this->serviceManager->get('translator');

            if (isset($config['locales'][$lang]))
            {
                $locale = $config['locales'][$lang];
                $translator->setLocale($locale);
            }
        }
    }
}