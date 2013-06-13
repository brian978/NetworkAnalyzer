<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Controller;

use Devices\Model\BandwidthLogs;
use Library\Mvc\Controller\AbstractFormController;

class IndexController extends AbstractFormController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '\Reports\Form\InterfaceBandwidth',
        'object' => '\Reports\Entity\Report',
        'model' => 'Devices\Model\DevicesModel',
        'dataKey' => 'report',
    );

    public function interfaceBandwidthAction()
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
     * This is a dispatcher for various reports
     *
     * @return array
     */
    public function generateAction()
    {
        $hasFailed  = true;
        $dispatch   = $this->getEvent()->getRouteMatch()->getParam('dispatch');
        $translator = $this->serviceLocator->get('translator');
        $model      = null;

        switch ($dispatch) {
            case 'interfaceBandwidth':
                $reportTitle = $translator->translate('Interface bandwidth report');

                /** @var $adapter \Zend\Db\Adapter\Adapter */
                $adapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
                $model   = new BandwidthLogs($adapter);
                break;

            default:
                $reportTitle = '';
                break;
        }

        if ($this->request->isPost() && $model !== null) {
            $form    = $this->getForm($this->request->getPost()->toArray());
            $isValid = $form->isValid();

            // Redirect regarding if valid or not but with different params
            if ($isValid === true) {
                $hasFailed = false;
                $className = '\Reports\Model\Reports\\' . ucfirst($dispatch);

                $reportObject = new $className();
                $reportObject->setModel($model);
                $reportObject->setData($this->getRequest()->getPost()->toArray());
            }
        }

        if ($hasFailed === true) {
            $this->redirectOnFail(array('action' => $dispatch));
        }

        return array(
            'reportTitle' => $reportTitle,
        );
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function redirectOnSuccess(array $data)
    {
        unset($data);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function redirectOnFail(array $data)
    {
        $redirectUrl = $this->url()->fromRoute(
            'reports/status',
            array(
                'action' => $data['action'],
                'success' => 'false'
            ),
            true
        );

        $this->PostRedirectGet($redirectUrl, true);
    }
}
