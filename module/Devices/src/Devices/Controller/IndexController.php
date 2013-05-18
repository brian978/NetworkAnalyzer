<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Library\Form\AbstractForm;

class IndexController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '\Devices\Form\DevicesFrom',
        'object' => '\Devices\Entity\Device',
        'model' => 'Devices\Model\DevicesModel',
    );

    /**
     *
     * @param \Library\Form\AbstractForm $form
     * @param \ArrayAccess $object
     */
    protected function populateEditData(AbstractForm $form, \ArrayAccess $object)
    {
        // Arranging the data properly so that the form would be auto-populated
        $form->setData(array(
            'device' => array(
                'id' => $object->id,
                'name' => $object->name,
                'location' => array(
                    'id' => $object->location_id
                ),
                'type' => array(
                    'id' => $object->type_id
                )
            )
        ));
    }
}