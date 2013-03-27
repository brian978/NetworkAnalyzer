<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace UI\Controller;

use Design\Library\Components;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractUiController extends AbstractActionController
{
    /**
     * @var \Design\Library\Components
     */
    protected $design;

    /**
     * Dispatch a request
     *
     * @events dispatch.pre, dispatch.post
     * @param  Request $request
     * @param  null|Response $response
     * @return Response|mixed
     */
    public function dispatch(Request $request, Response $response = null)
    {
        parent::dispatch($request, $response);

        $this->design = new Components();
        $this->getEvent()->getViewModel()->design = $this->design;

        $this->buildAdminMenu();
    }

    protected function buildAdminMeniu()
    {

    }
}