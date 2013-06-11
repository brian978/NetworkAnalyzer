<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldset;

use Devices\Entity\Device as DeviceEntity;
use Library\Form\Fieldset\AbstractDbFieldset;

class Device extends AbstractDbFieldset
{
    protected $modelName = 'Devices\Model\DevicesModel';

    public function __construct()
    {
        parent::__construct('device');

        $this->setObject(new DeviceEntity());
    }

    public function loadElements()
    {
        // Adding the elements to the fieldset
        $this->add($this->getHiddenId());

        $this->add(
            array(
                'name' => 'name',
                'options' => array(
                    'label' => $this->translator->translate('Name'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );

        $this->add(
            array(
                'name' => 'snmpVersion',
                'type' => '\Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translator->translate('SNMP Version'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => array(
                        \SNMP::VERSION_1 => 'VERSION_1',
                        \SNMP::VERSION_2C => 'VERSION_2C',
                        \SNMP::VERSION_3 => 'VERSION_3',
                    )
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );

        $this->add(
            array(
                'name' => 'snmpCommunity',
                'options' => array(
                    'label' => $this->translator->translate('SNMP Community'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );

        /**
         * -------------------------
         * DEVICE TYPE FIELDSET
         * -------------------------
         */
        $type = $this->buildFieldset(new Type());
        $type->setDenyFilters(array('name'));
        $type->loadElements();
        $this->add($type);

        // Changing the label of the type field
        $type->get('id')->setLabel($this->translator->translate('Device type'));

        /**
         * -------------------------
         * INTERFACE FIELDSET
         * -------------------------
         */
        $interface = $this->buildFieldset(new Iface());
        $interface->setDenyFilters(array('id'));
        $interface->loadElements();
        $this->add($interface);

        // Changing the label of the interface type field
        $interface->get('type')->get('id')->setLabel($this->translator->translate('Interface type'));
    }
}
