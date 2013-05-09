<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Form\Fieldsets;

use Dashboard\Entity\Device as DeviceEntity;

class Device extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('device');

        $this->setObject(new DeviceEntity());

        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array(
                    'class' => 'form_row'
                ),
            ),
            'attributes' => array(
                'required' => 'true'
            )
        ));

        $this->add(new Location());
        $this->add(new DeviceType());
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 10
                        )
                    )
                )
            )
        );
    }
}