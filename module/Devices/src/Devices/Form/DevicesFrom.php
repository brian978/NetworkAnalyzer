<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldsets\Device;
use Library\Form\AbstractForm;

class DevicesFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('devices_form');

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
        $device = new Device();
        $device->setUseAsBaseFieldset(true);
        $device->setServiceLocator($this->serviceLocator);
        $device->loadElements();

        // Adding the elements
        $this->add($device);

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
                    'value' => $this->translator->translate('Send')
                )
            )
        );

        return $this;
    }
}