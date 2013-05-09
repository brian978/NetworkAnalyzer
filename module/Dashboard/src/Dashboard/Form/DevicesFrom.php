<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods;

class DevicesFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('devices_form');

        $this->setHydrator(new ClassMethods(false))
            ->setInputFilter(new InputFilter());

        $this->setAttributes(array(
            'id' => 'devices_form',
            'class' => 'input_form'
        ));

        $this->add(
            array(
                'type' => 'Dashboard\Form\Fieldsets\Device',
                'options' => array(
                    'use_as_base_fieldset' => true
                )
            )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Send'
                )
            )
        );
    }
}