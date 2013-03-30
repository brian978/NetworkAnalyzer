<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Settings\Controller;

use UI\Controller\AbstractUiController;

class UsersController extends AbstractUiController
{
    protected function checkAcl()
    {
        return $this->acl->isAllowed('guest', 'users', 'access');
    }

    public function indexAction()
    {

    }

    public function addFormAction()
    {
        echo __METHOD__;

        return '';
    }

    public function listAction()
    {
        echo __METHOD__;

        return '';
    }

    public function profileAction()
    {
        echo __METHOD__;

        return '';
    }
}