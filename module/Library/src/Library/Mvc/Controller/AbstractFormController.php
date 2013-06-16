<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Mvc\Controller;

use Library\Form\AbstractForm;
use Library\Model\DbModelAwareInterface;
use Library\Model\ModelAwareInterface;
use UI\Controller\AbstractUiController;

abstract class AbstractFormController extends AbstractUiController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '',
        'object' => '',
        'model' => '',
        'dataKey' => '',
    );

    /**
     * @param AbstractForm $form
     * @param \ArrayAccess $object
     *
     * @return void
     */
    protected function populateEditData(
        AbstractForm $form,
        \ArrayAccess $object
    ) {
        // Arranging the data properly so that the form would be auto-populated
        $form->setData(
            array(
                $this->formSpecs['dataKey'] => array(
                    'id' => $object->id,
                    'name' => $object->name,
                )
            )
        );
    }

    /**
     * @param array $data
     *
     * @return void
     */
    abstract protected function redirectOnSuccess(array $data);

    /**
     * @param array $data
     *
     * @return void
     */
    abstract protected function redirectOnFail(array $data);

    /**
     * Returns a form instance built with the given $data and $params
     *
     * @param array $data
     * @param array $params
     *
     * @return \Library\Form\AbstractForm
     */
    protected function getForm(array $data = array(), $params = array())
    {
        /** @var $factory \Library\Form\Factory */
        $factory = $this->serviceLocator->get('TranslatableFormFactory');

        /** @var $form \Library\Form\AbstractForm */
        $form = $factory->createForm(
            array(
                'type' => $this->formSpecs['type']
            )
        );

        // Injecting dependencies
        if ($form instanceof DbModelAwareInterface) {
            $form->setModel($this->getModel());
        }

        // The form "mode" must be set before the loadElements
        // because it's used when retrieving the baseFieldset
        if (isset($params['form_mode'])) {
            $form->mode = $params['form_mode'];
        }

        $form->loadElements();

        if (!empty($this->formSpecs['object'])) {
            $form->bind(new $this->formSpecs['object']());
        }

        $form->setData($data);

        return $form;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var $model \Library\Model\AbstractDbModel */
        $model = $this->getModel();

        return array(
            'items' => $model->fetch()
        );
    }

    /**
     * The method is only used to show the form
     *
     * @return array
     */
    public function addFormAction()
    {
        $viewParams   = array();
        $post         = array();
        $successParam = $this->getEvent()->getRouteMatch()->getParam('success');
        $success      = null;

        if ($successParam !== null) {
            $success = filter_var($successParam, FILTER_VALIDATE_BOOLEAN);
        }

        // Loading the POST data
        if (is_array($tmpPost = $this->PostRedirectGet())) {
            $post = $tmpPost;
        }

        $form = $this->getForm($post);

        // We need to call the isValid method or else we won't have any error messages
        if (!empty($post)) {
            $form->isValid();
        }

        // Adding view params
        $viewParams['success'] = $success;
        $viewParams['form']    = $form;

        return $viewParams;
    }

    /**
     * The method is only used to show the form
     *
     * @return array
     */
    public function editFormAction()
    {
        $viewParams   = array();
        $post         = array();
        $successParam = $this->getEvent()->getRouteMatch()->getParam('success');
        $success      = null;

        if ($successParam !== null) {
            $success = filter_var($successParam, FILTER_VALIDATE_BOOLEAN);
        }

        // Loading the POST data
        if (is_array($tmpPost = $this->PostRedirectGet())) {
            $post = $tmpPost;
        }

        $form = $this->getForm(
            $post,
            array('form_mode' => AbstractForm::MODE_EDIT)
        );

        // We need to call the isValid method or else we won't have any error messages
        if (!empty($post)) {
            $form->isValid();
        } else {
            /** @var $model \Library\Model\AbstractDbModel */
            $model  = $this->serviceLocator->get($this->formSpecs['model']);
            $object = $model->getInfo($this->params('id'));

            if (is_object($object) && $object instanceof \ArrayAccess) {
                $this->populateEditData($form, $object);
            }
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
    public function processAction()
    {
        $action    = 'list';
        $hasFailed = true;

        if ($this->request->isPost()) {
            $form    = $this->getForm($this->request->getPost()->toArray());
            $isValid = $form->isValid();

            if ($form->getObject()->getId() !== 0) {
                $action = 'editForm';
            } else {
                $action = 'addForm';
            }

            // Redirect regarding if valid or not but with different params
            if ($isValid === true) {
                /** @var $model \Library\Model\AbstractDbModel */
                $model  = $this->serviceLocator->get($this->formSpecs['model']);
                $result = $model->save($form->getObject());

                if ($result > 0) {
                    $hasFailed = false;
                }
            }
        }

        if ($hasFailed === true) {
            $this->redirectOnFail(array('action' => $action));
        } else {
            $this->redirectOnSuccess(array('action' => $action));
        }

        return '';
    }

    /**
     * @return array|object
     */
    protected function getModel()
    {
        /** @var $model \Library\Model\AbstractDbModel */
        return $this->serviceLocator->get($this->formSpecs['model']);
    }

    public function deleteAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $id         = $routeMatch->getParam('id');

        if ($id != null) {

            /** @var $model \Library\Model\AbstractDbModel */
            $model = $this->getModel();

            $result = $model->doDelete($model->getInfo($id));

            if (is_numeric($result) && $result > 0) {
                $this->redirectOnSuccess(array('action' => 'index'));
            }
        }

        $this->redirectOnFail(array('action' => 'index'));
    }
}
