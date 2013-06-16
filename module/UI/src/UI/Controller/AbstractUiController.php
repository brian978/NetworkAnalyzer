<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace UI\Controller;

use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

abstract class AbstractUiController extends AbstractActionController
{
    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    protected $acl;

    /**
     *
     * @var \AAA\Model\Authorization
     */
    protected $userAuth;

    /**
     * Dispatch a request
     *
     * @events dispatch.pre, dispatch.post
     *
     * @param  Request       $request
     * @param  null|Response $response
     *
     * @return Response|mixed
     */
    public function dispatch(Request $request, Response $response = null)
    {
        $this->userAuth = $this->serviceLocator->get('authorization');
        $this->acl      = $this->serviceLocator->get('acl');

        parent::dispatch($request, $response);
    }

    /**
     * Execute the request
     *
     * @param \Zend\Mvc\MvcEvent $e
     *
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->checkAcl()) {
            /** @var $routeMatch \Zend\Mvc\Router\RouteMatch */
            $routeMatch = $e->getRouteMatch();
            $routeMatch->setParam('action', 'denied');
        }

        return parent::onDispatch($e);
    }

    /**
     * Action used when the user tries to access a denied page
     *
     * @return ViewModel
     */
    public function deniedAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('error/denied');

        return $viewModel;
    }

    /**
     * Empty method that can be used to implement an ACL check without modifying the dispatch method
     *
     * @throws \RuntimeException
     * @return boolean
     */
    protected function checkAcl()
    {
        $allow       = false;
        $config      = $this->serviceLocator->get('config');
        $permissions = $config['permissions']['controllers'];
        $controller  = $this->params('controller');
        $action      = $this->params('action');
        $resource    = null;
        $privilege   = null;

        // If there is not permission set for a given controller we allow the response
        if (!isset($permissions[$controller])) {
            $allow = false;
        } else {
            if (!isset($permissions[$controller]['resource']) || !isset($permissions[$controller]['privileges'])) {
                throw new \RuntimeException('Configuration invalid, missing "resource" or "privileges" entry');
            }

            $resource = $permissions[$controller]['resource'];

            if (isset($permissions[$controller]['privileges'][$action])) {
                $privilege = $permissions[$controller]['privileges'][$action];
            } elseif (isset($permissions[$controller]['privileges']['all'])) {
                $privilege = $permissions[$controller]['privileges']['all'];
            }
        }

        if (!is_null($resource) && !is_null($privilege)) {
            // Checking if the privilege is actually a pointer to an inheritance
            if (isset($permissions[$controller]['privileges'][$privilege])) {
                $privilege = $permissions[$controller]['privileges'][$privilege];
            }

            $allow = $this->acl->isAllowed(
                $this->userAuth->getRole(),
                $resource,
                $privilege
            );
        }

        return $allow;
    }
}
