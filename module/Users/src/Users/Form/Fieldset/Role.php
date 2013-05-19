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
use Users\Entity\Role as RoleEntity;

class Role extends AbstractFieldset
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
        parent::__construct('role');

        $this->setObject(new RoleEntity());
    }

    public function loadElements()
    {
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
    }

    /**
     * Builds an array of options for the select box
     *
     * @return array
     */
    protected function getValueOptions()
    {
        $options = array();

        if(is_object($this->model))
        {
            $locale = $this->getServiceLocator()->get('translator')->getLocale();

            $options = $this->model->fetch();

            foreach ($options as $value => $row)
            {
                $options[$value] = $row['name_' . $locale];
            }
        }

        $options = array_merge(array(
            0 => '...'
        ), $options);

        return $options;
    }

    protected function getIdElement()
    {
        if ($this->mode == self::MODE_SELECT)
        {
            $this->setModel('Users\Model\RolesModel');

            $element = array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'id',
                'options' => array(
                    'label' => 'Role',
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
        return $this->processDenyFilters($this->getGenericInputFilterSpecs());
    }
}