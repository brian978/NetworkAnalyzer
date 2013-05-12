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

class LocationsController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formParams = array(
        'type' => '\Devices\Form\LocationsFrom',
        'object' => '\Devices\Entity\Location',
        'model' => 'Devices\Model\LocationsModel',
    );

    protected function populateEditData(AbstractForm $form)
    {
        /** @var $model \Library\Model\AbstractDbModel */
        $model  = $this->serviceLocator->get($this->formParams['model']);
        $object = $model->getInfo($this->params('id'));

        if(is_object($object) && $object instanceof \ArrayAccess)
        {
            // Arranging the data properly so that the form would be auto-populated
            $data = array(
                'location' => array(
                    'id' => $object->id,
                    'name' => $object->name,
                )
            );

            $form->setData($data);
        }
    }
}