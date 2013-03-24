<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Auth\Controller;

use Auth\Model\Authentication;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $auth = new Authentication($this->serviceLocator);
        var_dump($auth->setCredentials(array())->authenticate());
    }

    public function logoutAction()
    {
        $this->redirect()->toRoute('auth', array('action' => 'index'), true);

        return;
    }
}
