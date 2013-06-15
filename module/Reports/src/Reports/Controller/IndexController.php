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
use Sniffer\Model\TrafficLogs;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractFormController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'object' => '\Reports\Entity\Report',
        'model' => 'Devices\Model\DevicesModel',
        'dataKey' => 'report',
    );

    /**
     * @return array
     */
    protected function getViewParams()
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

    public function interfacesTrafficAction()
    {
        $this->formSpecs['type'] = '\Reports\Form\InterfacesTraffic';

        return $this->getViewParams();
    }

    public function devicesUptimeAction()
    {
        $this->formSpecs['type'] = '\Reports\Form\DevicesUptime';

        return $this->getViewParams();
    }

    public function devicesTrafficAction()
    {
        $this->formSpecs['type'] = '\Reports\Form\DevicesTraffic';

        return $this->getViewParams();
    }

    /**
     * This is a dispatcher for various reports
     *
     * @return array
     */
    public function generateAction()
    {
        $dispatch = $this->getEvent()->getRouteMatch()->getParam('dispatch');
        $model    = null;

        switch ($dispatch) {
            case 'interfacesTraffic':
                $this->formSpecs['type'] = '\Reports\Form\InterfacesTraffic';

                /** @var $adapter \Zend\Db\Adapter\Adapter */
                $adapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
                $model   = new BandwidthLogs($adapter);
                break;

            case 'devicesUptime':
                $this->formSpecs['type'] = '\Reports\Form\DevicesUptime';

                /** @var $adapter \Zend\Db\Adapter\Adapter */
                $adapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
                $model   = new BandwidthLogs($adapter);
                break;

            case 'devicesTraffic':
                $this->formSpecs['type'] = '\Reports\Form\DevicesTraffic';

                /** @var $adapter \Zend\Db\Adapter\Adapter */
                $adapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
                $model   = new TrafficLogs($adapter);
                break;

            default:
                break;
        }

        if ($this->request->isPost() && $model !== null) {
            $post = $this->request->getPost()->toArray();

            $form    = $this->getForm($post);
            $isValid = $form->isValid();

            // Redirect regarding if valid or not but with different params
            if ($isValid === true) {
                $className = '\Reports\Model\Reports\\' . ucfirst($dispatch);

                /** @var $reportObject \Reports\Model\Reports\ReportInterface */
                $reportObject = new $className();
                $reportObject->setModel($model);
                $reportObject->setData($post);
                $reportData = $reportObject->getReport();

                $deviceInfo = '';

                if (isset($post['interface_bandwidth']) || isset($post['devices_traffic'])) {
                    $deviceData = current($post);

                    /** @var $devicesModel \Devices\Model\DevicesModel */
                    $devicesModel = $this->serviceLocator->get('Devices\Model\DevicesModel');
                    $deviceInfo   = $devicesModel->getInfo($deviceData['device']['id']);
                }

                // View stuff
                $this->layout('layout/report.phtml');

                $viewModel = new ViewModel(array(
                    'reportData' => $reportData,
                    'deviceInfo' => $deviceInfo,
                ));

                $viewModel->setTemplate('reports/index/' . $dispatch . 'Report');

                return $viewModel;
            }
        }

        $this->redirectOnFail(array('action' => $dispatch));
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
