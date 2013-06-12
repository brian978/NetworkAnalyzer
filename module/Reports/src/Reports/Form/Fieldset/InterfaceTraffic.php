<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Form\Fieldset;

use Library\Form\Fieldset\AbstractDbFieldset;

class InterfaceTraffic extends AbstractDbFieldset
{
    public function __construct()
    {
        parent::__construct('interface_traffic');

        $this->setObject(new \stdClass());
    }

    public function loadElements()
    {
        $this->add($this->getSelectId($this->translator->translate('Devices')));
    }
}