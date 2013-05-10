<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldsets;

use Devices\Entity\Type as TypeEntity;
use Library\Form\Fieldsets\AbstractFieldset;

class DeviceType extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('type');

        $this->setObject(new TypeEntity());

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id',
                'options' => array(
                    'label' => 'Type',
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
        foreach($this->denyFilters as $input)
        {
            if(isset($filters[$input]))
            {
                unset($filters[$input]);
            }
        }

        return $filters;
    }
}