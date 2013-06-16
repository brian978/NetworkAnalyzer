<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

use Library\Mvc\Controller\AbstractFormController;

abstract class AbstractController extends AbstractFormController
{
    /**
     * @param array $data
     */
    protected function redirectOnSuccess(array $data)
    {
        $this->redirect()
            ->toRoute(
                'devices/status',
                array('action' => $data['action'], 'success' => 'true'),
                true
            );
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function redirectOnFail(array $data)
    {
        $url = $this->url()->fromRoute(
            'devices/status',
            array('action' => $data['action'], 'success' => 'false'),
            true
        );

        $this->PostRedirectGet($url);
        $this->redirect()->toUrl($url);
    }
}
