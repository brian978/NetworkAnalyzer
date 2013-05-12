<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Users\Controller;

use Library\Mvc\Controller\AbstractFormController;

abstract class AbstractController extends AbstractFormController
{
    /**
     * @param array $data
     */
    protected function redirectOnSuccess(array $data)
    {
        $this->redirect()
            ->toRoute('users/status', array('action' => $data['action'], 'success' => 'true'), true);
    }

    /**
     * @param array $data
     * @return void
     */
    protected function redirectOnFail(array $data)
    {
        $this->PostRedirectGet(
            $this->url()->fromRoute('users/status', array('action' => $data['action'], 'success' => 'false'), true),
            true
        );
    }
}