<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Form\Fieldset;

use Library\Form\Fieldset\AbstractFieldset;
use Users\Entity\User as UserEntity;

class User extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('user');

        $this->setObject(new UserEntity());
    }

    public function loadElements()
    {
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'value' => 0
            )
        ));

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