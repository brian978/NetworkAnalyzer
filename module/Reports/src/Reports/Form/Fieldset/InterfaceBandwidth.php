<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Form\Fieldset;

use Devices\Form\Fieldset\Device;
use Library\Form\Fieldset\AbstractDbFieldset;
use Reports\Entity\Report;

class InterfaceBandwidth extends AbstractDbFieldset
{
    public function __construct()
    {
        parent::__construct('interface_bandwidth');

        $this->setObject(new Report());
    }

    public function loadElements()
    {
        $devices       = $this->buildFieldset(new Device());
        $devices->mode = self::MODE_SELECT;
        $devices->setDenyFilters(array('name', 'snmpVersion', 'snmpCommunity'));
        $devices->loadElements();
        $this->add($devices);
    }
}
