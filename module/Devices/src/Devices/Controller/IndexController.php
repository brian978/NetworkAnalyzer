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
use SNMP\Manager\ObjectManager;
use SNMP\Manager\SessionManager;
use SNMP\Model\Session;

class IndexController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs
        = array(
            'type' => '\Devices\Form\DevicesFrom',
            'object' => '\Devices\Entity\Device',
            'model' => 'Devices\Model\DevicesModel',
        );

    /**
     *
     * @param \Library\Form\AbstractForm $form
     * @param \ArrayAccess               $object
     */
    protected function populateEditData(
        AbstractForm $form,
        \ArrayAccess $object
    ) {
        // Arranging the data properly so that the form would be auto-populated
        $form->setData(
            array(
                'device' => array(
                    'id' => $object->id,
                    'name' => $object->name,
                    'snmpVersion' => $object->snmp_version,
                    'snmpCommunity' => $object->snmp_community,
                    'type' => array(
                        'id' => $object->type_id
                    ),
                    'interface' => array(
                        'ip' => $object->ip,
                        'type' => array(
                            'id' => $object->interface_type_id
                        )
                    )
                )
            )
        );
    }

    public function monitorAction()
    {
        // Setting a refresh interval for the page
        /** @var  $headers \Zend\Http\Headers */
        $headers = $this->getResponse()->getHeaders();
        $headers->addHeaderLine('Refresh', 3);

        /** @var $model \Library\Model\AbstractDbModel */
        $model      = $this->getModel();
        $deviceInfo = $model->getInfo(
            $this->getEvent()->getRouteMatch()->getParam('id')
        );

        $config = array(
            'version' => $deviceInfo->snmp_version,
            'hostname' => $deviceInfo->ip,
            'community' => $deviceInfo->snmp_community,
        );

        // Manager objects
        $objectManager = new ObjectManager(new SessionManager(new Session($this->serviceLocator, $config)));
        $device        = $objectManager->getDevice();

        return array(
            'device' => $device,
            'deviceInfo' => $deviceInfo,
        );
    }
}
