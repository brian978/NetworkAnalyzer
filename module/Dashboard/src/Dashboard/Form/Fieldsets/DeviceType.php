<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Form\Fieldsets;

use Dashboard\Entity\Type as TypeEntity;

class DeviceType extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('device_type');

        $this->setObject(new TypeEntity());

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'type',
                'options' => array(
                    'label' => 'Type',
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => array(
                        0 => '...',
                        1 => 'Switch',
                        2 => 'Router',
                    )
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );
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
            'type' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'greater_than',
                        'options' => array(
                            'min' => 0,
                            'message' => 'You must select a value'
                        )
                    )
                )
            )
        );
    }
}