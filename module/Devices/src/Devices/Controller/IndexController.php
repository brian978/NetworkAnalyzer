<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Library\Form\AbstractForm;
use SNMP\Session;

class IndexController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs
        = array(
            'type'   => '\Devices\Form\DevicesFrom',
            'object' => '\Devices\Entity\Device',
            'model'  => 'Devices\Model\DevicesModel',
        );

    /**
     *
     * @param \Library\Form\AbstractForm $form
     * @param \ArrayAccess               $object
     */
    protected function populateEditData(AbstractForm $form, \ArrayAccess $object)
    {
        // Arranging the data properly so that the form would be auto-populated
        $form->setData(
            array(
                 'device' => array(
                     'id'             => $object->id,
                     'name'           => $object->name,
                     'snmp_version'   => $object->snmp_version,
                     'snmp_community' => $object->snmp_community,
                     'location'       => array(
                         'id' => $object->location_id
                     ),
                     'type'           => array(
                         'id' => $object->type_id
                     )
                 )
            )
        );
    }

    public function monitorAction()
    {
        /** @var $model \Library\Model\AbstractDbModel */
        $model      = $this->getModel();
        $deviceInfo = $model->getInfo($this->getEvent()->getRouteMatch()->getParam('id'));

        $config = array(
            'version'   => $deviceInfo->snmp_version,
            'hostname'  => $deviceInfo->ip,
            'community' => $deviceInfo->snmp_community,
        );

        $snmpSession = new Session($this->serviceLocator, $config);
        $output      = $snmpSession->walk('.');

        return array(
            'sessionOutput' => $output
        );
    }
}