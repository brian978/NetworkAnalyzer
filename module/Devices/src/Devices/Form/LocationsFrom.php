<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldsets\Location;
use Library\Form\AbstractForm;

class LocationsFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('locations_form');

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
        $locationFieldset = new Location();
        $locationFieldset->setUseAsBaseFieldset(true);

        // Removing some filters from the fieldset
        $locationFieldset->setDenyFilters(array('id'));

        // Adding the elements
        $this->add($locationFieldset);

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