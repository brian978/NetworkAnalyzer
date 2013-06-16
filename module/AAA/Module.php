<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace AAA;

use AAA\Model\Authentication;
use Library\Module as MainModule;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack;

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

    public function onBootstrap(MvcEvent $event)
    {
        $app            = $event->getApplication();
        $serviceManager = $app->getServiceManager();

        /** @var $response Response */
        $response = $event->getResponse();

        if ($response instanceof Response) {
            $auth = new Authentication($serviceManager);

            /** @var $router TreeRouteStack */
            $router = $event->getRouter();

            $routeMatch = $router->match($event->getRequest());
            $routeName  = $routeMatch->getMatchedRouteName();

            if (!$auth->hasIdentity()) {
                if ($routeName != 'auth') {
                    $url = $router->assemble(
                        array(),
                        array(
                            'name' => 'auth',
                        )
                    );
                }
            } else {
                if ($routeName == 'auth') {
                    $url = $router->assemble(
                        array(),
                        array(
                            'name' => 'index',
                        )
                    );
                }
            }

            if (!empty($url)) {
                $response->getHeaders()->addHeaderLine('Location: ' . $url);
                $response->setStatusCode(302);
                $response->renderStatusLine();

                return $response;
            }
        }
    }
}
