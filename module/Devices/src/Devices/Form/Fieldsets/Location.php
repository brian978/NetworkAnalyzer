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
use Library\Form\Fieldsets\AbstractFieldset;

class Location extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('location');

        $this->setObject(new LocationEntity());
    }

    public function loadElements()
    {
        $this->setModel('Devices\Model\LocationsModel');

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id',
                'options' => array(
                    'label' => 'Location',
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => $this->getValueOptions()
                ),
                'attributes' => array(
                    'required' => true
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