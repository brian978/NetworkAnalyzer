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
    protected $formParams = array(
        'type' => '\Devices\Form\DevicesFrom',
        'object' => '\Devices\Entity\Device',
        'model' => 'Devices\Model\DevicesModel',
    );

    /**
     *
     * @param \Library\Form\AbstractForm $form
     */
    protected function populateEditData(AbstractForm $form)
    {
        /** @var $model \Library\Model\AbstractDbModel */
        $model  = $this->serviceLocator->get($this->formParams['model']);
        $object = $model->getInfo($this->params('id'));

        if(is_object($object) && $object instanceof \ArrayAccess)
        {
            // Arranging the data properly so that the form would be auto-populated
            $data = array(
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
            );

            $form->setData($data);
        }
    }
}