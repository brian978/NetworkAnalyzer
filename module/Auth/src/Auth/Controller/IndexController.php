<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Auth\Controller;

use Snmp\Session;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $snmp = new Session($this->serviceLocator);

        var_dump($snmp->get('sysDescr.0'));
    }

    public function logoutAction()
    {
        $this->redirect()->toRoute('auth', array('action' => 'index'), true);

        return;
    }
}
