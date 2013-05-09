<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Devices\Entity\Device;
use Devices\Form\DevicesFrom;
use Devices\Model\DevicesModel;
use UI\Controller\AbstractUiController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractUiController
{
    public function addFormAction()
    {
        $form   = new DevicesFrom();
        $device = new Device();
        $form->bind($device);

        if ($this->request->isPost())
        {
            $form->setData($this->request->getPost());

            if ($form->isValid())
            {
                $model = new DevicesModel();
                $model->save($device);
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