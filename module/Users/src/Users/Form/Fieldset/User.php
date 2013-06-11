<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Form\Fieldset;

use Library\Form\Fieldset\AbstractDbFieldset;
use Users\Entity\User as UserEntity;

class User extends AbstractDbFieldset
{
    public function __construct()
    {
        parent::__construct('user');

        $this->setObject(new UserEntity());
    }

    public function loadElements()
    {
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
                'name' => 'email',
                'options' => array(
                    'label' => 'Email',
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );

        $role = $this->buildFieldset(new Role());
        $role->setDenyFilters(array('name'));
        $role->mode = Role::MODE_SELECT;
        $role->loadElements();

        $this->add($role);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters          = $this->getGenericInputFilterSpecs();
        $filters['email'] = array(
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                )
            )
        );

        // Removing the un-required filters (this is useful when you don't show all the fields)
        return $this->processDenyFilters($filters);
    }
}
