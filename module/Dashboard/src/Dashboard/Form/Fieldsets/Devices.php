<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Form\Fieldsets;

use Dashboard\Entity\Device;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class Devices extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('device');

        $this->setHydrator(new ClassMethods(false));
//        $this->setObject(new Device());

        $this->add(
            array(
                'name' => 'name',
                'options' => array(
                    'label' => 'Name'
                ),
                'attributes' => array(
                    'required' => 'required'
                )
            )
        );
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
                'required' => true
            )
        );
    }
}