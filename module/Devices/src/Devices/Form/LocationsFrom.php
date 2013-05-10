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
        $location = new Location();
        $location->setUseAsBaseFieldset(true);
        $location->setServiceLocator($this->serviceLocator);
        $location->loadElements();

        // Removing some filters from the fieldset
        $location->setDenyFilters(array('id'));

        // Adding the elements
        $this->add($location);

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