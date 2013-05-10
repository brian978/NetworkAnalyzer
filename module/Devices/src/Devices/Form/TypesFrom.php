<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldsets\Type;
use Library\Form\AbstractForm;

class TypesFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('types_form');

        $this->setAttributes(
            array(
                'class' => 'input_form'
            )
        );
    }

    /**
     * @return $this
     */
    public function loadElements()
    {
        $type = new Type();
        $type->setUseAsBaseFieldset(true);
        $type->setServiceLocator($this->serviceLocator);
        $type->setDenyFilters(array('id'));
        $type->loadElements();

        // Adding the elements
        $this->add($type);

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf'
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Send'
                )
            )
        );

        return $this;
    }
}