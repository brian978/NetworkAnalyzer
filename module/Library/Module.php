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