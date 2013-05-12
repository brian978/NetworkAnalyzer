<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldset;

use Devices\Entity\Iface as IfaceEntity;
use Library\Form\Fieldset\AbstractFieldset;

class Iface extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('interface');

        $this->setObject(new IfaceEntity());
    }

    public function loadElements()
    {
        // Adding the elements to the fieldset
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'id',
                'options' => array(
                    'value' => 0
                )
            )
        );

        $this->add(
            array(
                'name' => 'name',
                'options' => array(
                    'label' => 'Name',
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
                'name' => 'mac',
                'options' => array(
                    'label' => 'MAC',
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
                'name' => 'ip',
                'options' => array(
                    'label' => 'IP',
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );

        $device       = new Device();
        $device->mode = Device::MODE_SELECT;
        $device->setServiceLocator($this->serviceLocator);
        $device->setDenyFilters(array('name'));
        $device->loadElements();

        $type = new IfaceType();
        $type->setServiceLocator($this->serviceLocator);
        $type->setDenyFilters(array('name'));
        $type->loadElements();

        $this->add($type);
        $this->add($device);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = array(
            'id' => array(
                'validators' => array(
                    array(
                        'name' => 'greater_than',
                        'options' => array(
                            'min' => 0,
                            'message' => 'You must select a value'
                        )
                    )
                )
            ),
            'name' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3
                        )
                    )
                )
            )
        );

        // Removing the un-required filters (this is useful when you don't show all the fields)
        $this->processDenyFilters($filters);

        return $filters;
    }
}