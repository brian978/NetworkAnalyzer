<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form\Fieldset;

use Devices\Entity\Iface as IfaceEntity;
use Library\Form\Fieldset\AbstractFieldset;

class Iface extends AbstractFieldset
{
    public function __construct()
    {
        parent::__construct('interface');

        $this->setObject(new IfaceEntity());
        $this->setDenyFilters(array('name'));
    }

    public function loadElements()
    {
        $this->add(
            array(
                'name' => 'ip',
                'options' => array(
                    'label' => $this->translator->translate('IP'),
                    'label_attributes' => array(
                        'class' => 'form_row'
                    ),
                ),
                'attributes' => array(
                    'required' => 'true'
                )
            )
        );

        $type = new IfaceType();
        $type->setServiceLocator($this->serviceLocator);
        $type->setTranslator($this->translator);
        $type->setDenyFilters(array('name'));
        $type->loadElements();
        $this->add($type);
    }
}
