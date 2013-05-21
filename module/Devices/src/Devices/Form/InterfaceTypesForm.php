<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldset\IfaceType;

class InterfaceTypesForm extends InterfacesFrom
{
    /**
     * @return \Library\Form\Fieldset\AbstractFieldset
     */
    protected function getBaseFieldsetObject()
    {
        return $this->setupBaseFieldsetObject(new IfaceType());
    }
}
