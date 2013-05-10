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
use UI\Controller\AbstractUiController;

class IndexController extends AbstractUiController
{
    protected function getDeviceForm(array $data = array())
    {
        $form   = new DevicesFrom();
        $device = new Device();
        $form->bind($device);
        $form->setData($data);

        return $form;
    }

    /**
     * The method is only used to show the form
     *
     * @return array
     */
    public function addFormAction()
    {
        $viewParams = array();
        $post       = array();

        if (is_array($tmpPost = $this->PostRedirectGet()))
        {
            $post = $tmpPost;
        }

        $form = $this->getDeviceForm($post);

        // Adding view params
        if ((bool)$this->getEvent()->getRouteMatch()->getParam('success') === true)
        {
            $viewParams['success'] = $this->getServiceLocator()->get('translator')->translate('The device was added');
        }

        $viewParams['form'] = $form;

        return $viewParams;
    }

    public function listAction()
    {
    }

    public function addAction()
    {
        if ($this->request->isPost())
        {
            $form = $this->getDeviceForm($this->request->getPost()->toArray());

            if ($form->isValid())
            {
                /** @var $model \Devices\Model\DevicesModel */
                $model = $this->serviceLocator->get('Devices\Model\DevicesModel');
                $model->save($form->getObject());

                $this->redirect()->toRoute('devices/status', array('action' => 'addForm', 'success' => 'true'), true);
            }
            else
            {
                $this->PostRedirectGet(
                    $this->url()->fromRoute('devices/status', array('action' => 'addForm', 'success' => 'false'), true),
                    true
                );
            }
        }

        return '';
    }

    public function updateAction()
    {
    }

    public function removeAction()
    {
    }
}