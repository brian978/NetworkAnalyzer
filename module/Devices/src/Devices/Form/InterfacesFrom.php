<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Form;

use Devices\Form\Fieldsets\Iface;
use Library\Form\AbstractForm;

class InterfacesFrom extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('interfaces_form');

        $this->setAttributes(
            array(
                'class' => 'input_form'
            )
        );
    }

    /**
     * @return \Library\Form\Fieldsets\AbstractFieldset
     */
    protected function getBaseFieldsetObject()
    {
        $object = new Iface();
        $object->setUseAsBaseFieldset(true)
            ->setServiceLocator($this->serviceLocator)
            ->setDenyFilters(array('id'))
            ->loadElements();

        return $object;
    }
}