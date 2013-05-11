<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Navigation\Page;

use Zend\Navigation\Page\Mvc as ZendMvcPage;

class Mvc extends ZendMvcPage
{
    /**
     * Returns href for this page
     *
     * This method uses {@link RouteStackInterface} to assemble
     * the href based on the page's properties.
     *
     * @see RouteStackInterface
     * @return string  page href
     */
    public function getHref()
    {
        $this->params = $this->routeMatch->getParams();

        // Removing the controller and action params to allow the page config to set them
        unset($this->params['controller']);
        unset($this->params['action']);
        unset($this->params['id']);

        // Sync route match
        $this->routeMatch->setParam('controller', null);
        $this->routeMatch->setParam('action', null);
        $this->routeMatch->setParam('id', null);

        return parent::getHref();
    }
}