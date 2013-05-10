<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use UI\Controller\AbstractUiController;

class AbstractController extends AbstractUiController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formParams = array(
        'type' => '',
        'object' => '',
        'model' => '',
    );

    protected function getForm(array $data = array())
    {
        /** @var $factory \Library\Form\Factory */
        $factory = $this->serviceLocator->get('TranslatableFormFactory');

        /** @var $form \Devices\Form\DevicesFrom */
        $form   = $factory->createForm(array('type' => $this->formParams['type']));
        $object = new $this->formParams['object']();
        $form->loadElements()
            ->bind($object)
            ->setData($data);

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
        $success    = filter_var($this->getEvent()->getRouteMatch()->getParam('success'), FILTER_VALIDATE_BOOLEAN);

        // Loading the POST data
        if (is_array($tmpPost = $this->PostRedirectGet()))
        {
            $post = $tmpPost;
        }

        $form = $this->getForm($post);

        // We need to call the isValid method or else we won't have any error messages
        if (!empty($post))
        {
            $form->isValid();
        }

        // Adding view params
        $viewParams['success'] = $success;
        $viewParams['form']    = $form;

        return $viewParams;
    }

    /**
     * The method only adds the data from the form and it redirects back after (regarding if success or fail)
     *
     * @return string
     */
    public function addAction()
    {
        $hasFailed = true;

        if ($this->request->isPost())
        {
            $form = $this->getForm($this->request->getPost()->toArray());

            // Redirect regarding if valid or not but with different params
            if ($form->isValid())
            {
                /** @var $model \Library\Model\AbstractModel */
                $model  = $this->serviceLocator->get($this->formParams['model']);
                $result = $model->save($form->getObject());

                if ($result > 0)
                {
                    $hasFailed = false;

                    $this->redirect()
                        ->toRoute('devices/status', array('action' => 'addForm', 'success' => 'true'), true);
                }
            }

        }

        if ($hasFailed === true)
        {
            $this->PostRedirectGet(
                $this->url()->fromRoute('devices/status', array('action' => 'addForm', 'success' => 'false'), true),
                true
            );
        }

        return '';
    }

    public function listAction()
    {
    }
}