<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Controller;

use Library\Form\AbstractForm;

class IndexController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '\Users\Form\UsersForm',
        'object' => '\Users\Entity\User',
        'model' => 'Users\Model\UsersModel',
        'dataKey' => 'user',
    );

    /**
     * @param AbstractForm $form
     * @param \ArrayAccess $object
     * @return void
     */
    protected function populateEditData(AbstractForm $form, \ArrayAccess $object)
    {
        // Arranging the data properly so that the form would be auto-populated
        $form->setData(array(
            $this->formSpecs['dataKey'] => array(
                'id' => $object->id,
                'name' => $object->name,
                'email' => $object->email,
                'role' => array(
                    'id' => $object->role_id,
                )
            )
        ));
    }

    public function listAction()
    {
        /** @var $model \Library\Model\AbstractDbModel */
        $model = $this->serviceLocator->get($this->formSpecs['model']);

        return array(
            'items' => $model->fetch($this->getServiceLocator()->get('translator')->getLocale())
        );
    }

    protected function profileAction()
    {
    }
}