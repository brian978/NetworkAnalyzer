<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldsets\Iface;
use Library\Form\AbstractForm;

class InterfacesFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('interfaces_form');

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
        $interface = new Iface();
        $interface->setUseAsBaseFieldset(true);
        $interface->setServiceLocator($this->serviceLocator);
        $interface->setDenyFilters(array('id'));
        $interface->loadElements();

        // Adding the elements
        $this->add($interface);

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