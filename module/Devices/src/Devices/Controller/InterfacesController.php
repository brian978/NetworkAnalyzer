<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

class InterfacesController extends AbstractController
{
    protected $formParams = array(
        'type' => '\Devices\Form\InterfacesFrom',
        'object' => '\Devices\Entity\Iface',
        'model' => 'Devices\Model\InterfacesModel',
    );
}