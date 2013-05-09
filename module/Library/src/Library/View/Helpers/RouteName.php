<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\View\Helpers;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Renderer\RendererInterface as Renderer;

class RouteName extends AbstractHelper
{
    protected $routeName;

    /**
     * Set the View object
     *
     * @param  Renderer $view
     * @return AbstractHelper
     */
    public function setView(Renderer $view)
    {
        parent::setView($view);

        $this->routeName = $view->getHelperPluginManager()
            ->getServiceLocator()
            ->get('application')
            ->getMvcEvent()
            ->getRouteMatch()
            ->getMatchedRouteName();

        if ($this->routeName == 'home')
        {
            $this->routeName = 'index/module';
        }
    }

    public function __invoke()
    {
        return $this->routeName;
    }
}