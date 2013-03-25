<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class DevicesController extends AbstractActionController
{
    public function indexAction()
    {

    }

    public function addAction()
    {
        return new JsonModel(array('test' => 'test'));
    }

    public function updateAction()
    {

    }

    public function removeAction()
    {

    }
}