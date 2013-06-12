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
use Devices\Model\DevicesModel;
use Library\Mvc\Controller\AbstractFormController;

class IndexController extends AbstractFormController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '\Reports\Form\InterfaceTraffic',
        'object' => '\stdClass',
        'model' => 'Devices\Model\DevicesModel',
        'dataKey' => 'report',
    );

    public function interfaceTrafficAction()
    {
        $form = $this->getForm();

        return array(
            'form' => $form,
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
        unset($data);
    }
}