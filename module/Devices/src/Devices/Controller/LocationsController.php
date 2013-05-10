<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

class LocationsController extends AbstractController
{
    protected $formParams = array(
        'type' => '\Devices\Form\LocationsFrom',
        'object' => '\Devices\Entity\Location',
        'model' => 'Devices\Model\LocationsModel',
    );
}