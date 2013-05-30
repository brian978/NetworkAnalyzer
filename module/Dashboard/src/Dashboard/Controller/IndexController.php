<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Controller;

use SNMP\Session;
use UI\Controller\AbstractUiController;

class IndexController extends AbstractUiController
{
    public function updateAction()
    {
        // Updating the application
        var_dump(system('sh updateapp.sh'));
        die();

        $this->redirect()->toRoute('home');
    }
}
