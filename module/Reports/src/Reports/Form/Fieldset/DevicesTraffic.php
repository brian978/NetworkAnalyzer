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

class DevicesTraffic extends AbstractDbFieldset
{
    public function __construct()
    {
        parent::__construct('devices_traffic');

        $this->setObject(new Report());
    }

    public function loadElements()
    {
        $devices       = $this->buildFieldset(new Device());
        $devices->mode = self::MODE_SELECT;
        $devices->setDenyFilters(array('name', 'snmpVersion', 'snmpCommunity'));
        $devices->loadElements();
        $this->add($devices);

        $this->add(
            array(
                'name' => 'traffic_type',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translator->translate('Traffic type'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => array(
                        'tcp' => 'TCP',
                        'udp' => 'UDP',
                    )
                ),
                'attributes' => array(
                    'required' => true
                )
            )
        );

        $values       = range(1, 7);
        $valueOptions = array();

        foreach ($values as $value) {
            $valueOptions[$value] = $value;
        }

        $this->add(
            array(
                'name' => 'days',
                'type' => '\Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translator->translate('Days no.'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => $valueOptions
                )
            )
        );
    }

    protected function getValueOptions()
    {
        $options = parent::getValueOptions();
        unset($options[0]);

        return $options;
    }
}
