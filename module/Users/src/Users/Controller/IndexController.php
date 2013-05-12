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
    protected $formParams = array(
        'type' => '\Users\Form\UsersForm',
        'object' => '\Users\Entity\User',
        'model' => 'Users\Model\Users',
    );

    /**
     * @param AbstractForm $form
     * @return void
     */
    protected function populateEditData(AbstractForm $form)
    {
        // TODO: Implement populateEditData() method.
    }

    /**
     * @param array $data
     * @return void
     */
    protected function redirectOnSuccess(array $data)
    {
        // TODO: Implement redirectOnSuccess() method.
    }

    /**
     * @param array $data
     * @return void
     */
    protected function redirectOnFail(array $data)
    {
        // TODO: Implement redirectOnFail() method.
    }
}