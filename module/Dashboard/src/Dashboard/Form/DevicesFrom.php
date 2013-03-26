<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Form;

class DevicesFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('devices_form');

        $this->add(
            array(
                'type' => 'Dashboard\Form\Fieldsets\Devices',
                'options' => array(
                    'use_as_base_fieldset' => true
                )
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
    }
}