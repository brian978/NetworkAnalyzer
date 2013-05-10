<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldsets;

use Devices\Entity\Device as DeviceEntity;
use Library\Form\Fieldsets\AbstractFieldset;

class Device extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('device');

        $this->setObject(new DeviceEntity());

        $location   = new Location();
        $deviceType = new DeviceType();

        // We need to deny the filters we don't need
        $location->setDenyFilters(array('name'));
        $deviceType->setDenyFilters(array('name'));

        // Adding the elements to the fieldset
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
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'id',
                'options' => array(
                    'value' => 0
                )
            )
        );

        $this->add($location);
        $this->add($deviceType);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
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
    }
}