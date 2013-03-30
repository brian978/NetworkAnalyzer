<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Controller;

use Dashboard\Entity\Device;
use Dashboard\Form\DevicesFrom;
use UI\Controller\AbstractUiController;
use Zend\View\Model\JsonModel;

class DevicesController extends AbstractUiController
{
    protected function checkAcl()
    {
        return $this->acl->isAllowed('guest', 'devices', 'access');
    }

    public function addFormAction()
    {
        $form = new DevicesFrom();
        $device = new Device();
        $form->bind($device);

        if($this->request->isPost())
        {
            $form->setData($this->request->getPost());

            if($form->isValid())
            {
                var_dump($device);
            }
        }

        return array(
            'form' => $form
        );
    }

    public function listAction()
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