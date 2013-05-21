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
    protected $modelName = 'Users\Model\RolesModel';

    public function __construct()
    {
        parent::__construct('role');

        $this->setObject(new RoleEntity());
    }

    public function loadElements()
    {
        $this->add($this->getIdElement('Role'));

        $this->add(
            array(
                'name'       => 'name',
                'options'    => array(
                    'label'            => 'Name',
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

        if (is_object($this->model)) {
            $locale = $this->getServiceLocator()->get('translator')->getLocale();

            $options = $this->model->fetch();

            foreach ($options as $value => $row) {
                $options[$value] = $row['name_' . $locale];
            }
        }

        $options = array_merge(
            array(
                0 => '...'
            ),
            $options
        );

        return $options;
    }
}