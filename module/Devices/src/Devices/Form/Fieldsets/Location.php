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
use Devices\Model\AbstractModel;
use Library\Form\Fieldsets\AbstractFieldset;

class Location extends AbstractFieldset
{
    /**
     * @var AbstractModel
     */
    protected $model;

    public function __construct()
    {
        parent::__construct('location');

        $this->setObject(new LocationEntity());
        $this->setLabel('Location');
    }

    public function loadElements()
    {
        $this->setModel();

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
     * Initialized the model required for the database
     *
     */
    public function setModel()
    {
        $this->model = $this->serviceLocator->get('Devices\Model\LocationsModel');
    }

    /**
     * Builds an array of options for the select box
     *
     * @return array
     */
    protected function getValueOptions()
    {
        $options = $this->model->fetch();

        foreach ($options as $value => $row)
        {
            $options[$value] = $row['name'];
        }

        return $options;
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
        foreach ($this->denyFilters as $input)
        {
            if (isset($filters[$input]))
            {
                unset($filters[$input]);
            }
        }

        return $filters;
    }
}