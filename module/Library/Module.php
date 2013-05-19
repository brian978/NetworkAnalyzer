<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library;

use Library\Form\Factory;
use Zend\Form\FormElementManager;

class Module
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
                'TranslatableFormFactory' => function ($serviceManager)
                {
                    $formElementManager = new FormElementManager();
                    $factory            = new Factory($formElementManager);

                    // Injecting the serviceLocator into the formElementManager
                    $formElementManager->setServiceLocator($serviceManager);

                    return $factory;
                }
            )
        );
    }

    public function getConfig()
    {
        return include $this->moduleDir . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $this->moduleNamespace => $this->moduleDir . '/src/' . $this->moduleNamespace,
                ),
            ),
        );
    }
}