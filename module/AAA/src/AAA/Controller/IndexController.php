<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace AAA\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class IndexController extends AbstractActionController
{
    /**
     * Sets the proper layout
     *
     * @param MvcEvent $event
     * @return mixed
     */
    public function onDispatch(MvcEvent $event)
    {
        $this->layout('layout/aaa.phtml');

        return parent::onDispatch($event);
    }

    public function indexAction()
    {
        return array(
            'loginUrl' => $this->url()->fromRoute('auth', array('action' => 'login')),
        );
    }

    public function loginAction()
    {
        $success = false;

        if ($this->request->isPost()) {
            $auth = $this->serviceLocator->get('AAA\Authentication');
            $auth->setCredentials($this->getRequest()->getPost()->toArray());
            $result = $auth->authenticate();

            if ($result->isValid()) {
                $success = true;
            }
        }

        if ($success === true) {
            $this->redirect()->toRoute(
                'index',
                array('action' => 'index'),
                true
            );
        } else {
            $this->redirect()->toRoute(
                'auth',
                array('action' => 'index'),
                true
            );
        }
    }

    public function logoutAction()
    {
        $auth = $this->serviceLocator->get('AAA\Authentication');
        $auth->clearIdentity();

        $this->redirect()->toRoute('auth', array('action' => 'index'), true);
    }
}
