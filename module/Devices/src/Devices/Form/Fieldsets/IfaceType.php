<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldsets;

class IfaceType extends Type
{
    public function loadElements()
    {
        $this->setModel('Devices\Model\InterfaceTypesModel', true);

        parent::loadElements();
    }
}