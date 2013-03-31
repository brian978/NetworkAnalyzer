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
     * @param  Request       $request
     * @param  null|Response $response
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
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!$this->checkAcl())
        {
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
     * @return boolean
     */
    protected function checkAcl()
    {
        $response    = false;
        $config      = $this->serviceLocator->get('config');
        $permissions = $config['permissions']['controllers'];
        $controller  = $this->params('controller');
        $action      = $this->params('action');

        if (isset($permissions[$controller]['privileges'][$action]))
        {
            $resource  = $permissions[$controller]['resource'];
            $privilege = $permissions[$controller]['privileges'][$action];
            $response  = $this->acl->isAllowed($this->userAuth->getRole(), $resource, $privilege);
        }

        return $response;
    }
}