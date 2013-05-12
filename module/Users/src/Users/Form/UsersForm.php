<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Form;

use Library\Form\AbstractForm;
use Users\Form\Fieldset\User as UserFieldset;

class UsersForm extends AbstractForm
{
    /**
     * @return \Library\Form\Fieldset\AbstractFieldset
     */
    protected function getBaseFieldsetObject()
    {
        $object = new UserFieldset();
        $object->setUseAsBaseFieldset(true)->setServiceLocator($this->serviceLocator);

        if($this->mode == self::MODE_EDIT)
        {
            $object->setDenyFilters(array('id'));
        }

        $object->loadElements();

        return $object;
    }
}