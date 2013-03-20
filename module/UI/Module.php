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

class Module
{
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
        $this->eventManager->attach('dispatch', array($this, 'setLocale'));
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}