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
use Library\Form\Fieldset\AbstractFieldset;

class Device extends AbstractFieldset
{
    const MODE_SELECT = 1;
    const MODE_ADMIN  = 2;

    /**
     * Depending on this mode the object will add the ID element differently
     *
     * @var int
     */
    public $mode = self::MODE_ADMIN;

    public function __construct()
    {
        parent::__construct('device');

        $this->setObject(new DeviceEntity());
    }

    public function loadElements()
    {
        // Adding the elements to the fieldset
        $this->add($this->getIdElement());

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

        if ($this->mode == self::MODE_ADMIN)
        {
            // Creating the required fields set and injecting the dependencies
            $location = new Location();
            $location->setServiceLocator($this->serviceLocator);
            $location->setDenyFilters(array('name'));
            $location->loadElements();

            $type = new Type();
            $type->setServiceLocator($this->serviceLocator);
            $type->setDenyFilters(array('name'));
            $type->loadElements();

            $this->add($location);
            $this->add($type);
        }
    }

    protected function getIdElement()
    {
        if ($this->mode == self::MODE_SELECT)
        {
            $this->setModel('Devices\Model\DevicesModel');

            $element = array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id',
                'options' => array(
                    'label' => 'Device',
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => $this->getValueOptions()
                ),
                'attributes' => array(
                    'required' => true
                )
            );
        }
        else
        {
            $element = array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'id',
                'options' => array(
                    'value' => 0
                )
            );
        }

        return $element;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        // Removing the un-required filters (this is useful when you don't show all the fields)
        return $this->processDenyFilters($this->getGenericInputFilterSpecs());
    }
}