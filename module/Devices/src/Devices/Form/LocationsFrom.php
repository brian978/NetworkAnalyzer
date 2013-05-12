<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldset\Location;
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
     * @return \Library\Form\Fieldset\AbstractFieldset
     */
    protected function getBaseFieldsetObject()
    {
        $object = new Location();
        $object->setUseAsBaseFieldset(true)->setServiceLocator($this->serviceLocator);

        if($this->mode == self::MODE_EDIT)
        {
            $object->setDenyFilters(array('id'));
        }

        $object->loadElements();

        return $object;
    }
}