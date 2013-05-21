<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router'          => array(
        'routes' => array(
            'home'  => array(
                'type'    => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dashboard\Controller',
                        'controller'    => 'index',
                        'action'        => 'index',
                        'lang'          => 'en'
                    )
                )
            ),
            'index' => array(
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'options'       => array(
                    'route'    => '[/:lang]/home',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Dashboard\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'lang'          => 'en'
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'module' => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'       => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults'    => array()
                        )
                    )
                )
            ),
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'translator'              => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
    ),
    'translator'      => array(
        'locale'                    => array(
            'en_US',
            // Fallback locale
            'en_US'
        ),
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager'    => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map'             => array(),
    ),
);