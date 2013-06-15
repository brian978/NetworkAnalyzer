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

class DevicesUptime extends AbstractDbFieldset
{
    public function __construct()
    {
        parent::__construct('devices_uptime');

        $this->setObject(new Report());
    }

    public function loadElements()
    {
        $this->add(
            array(
                'name' => 'devices',
                'type' => 'Zend\Form\Element\MultiCheckbox',
                'options' => array(
                    'label' => $this->translator->translate('Device'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                    'value_options' => $this->getValueOptions()
                ),
                'attributes' => array()
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
