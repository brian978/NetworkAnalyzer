<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldsets\IfaceType;

class InterfaceTypesForm extends InterfacesFrom
{
    /**
     * @return \Library\Form\Fieldsets\AbstractFieldset
     */
    protected function getBaseFieldsetObject()
    {
        $object = new IfaceType();
        $object->setUseAsBaseFieldset(true)
            ->setServiceLocator($this->serviceLocator)
            ->setDenyFilters(array('id'))
            ->loadElements();

        return $object;
    }
}