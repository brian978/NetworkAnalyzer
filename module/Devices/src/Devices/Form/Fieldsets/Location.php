<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldsets;

use Devices\Entity\Location as LocationEntity;

class Location extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('location');

        $this->setObject(new LocationEntity());
        $this->setLabel('Location');

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id',
                'options' => array(
                    'label' => 'Location',
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => array(
                        0 => '...',
                        1 => 'Etaj 1',
                        2 => 'Etaj 2',
                    )
                ),
                'attributes' => array(
                    'required' => true
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
            )
        );
    }
}