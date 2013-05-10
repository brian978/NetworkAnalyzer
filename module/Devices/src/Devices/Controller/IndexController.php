<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

class IndexController extends AbstractController
{
    protected $formParams = array(
        'type' => '\Devices\Form\DevicesFrom',
        'object' => '\Devices\Entity\Device',
        'model' => 'Devices\Model\DevicesModel',
    );
}