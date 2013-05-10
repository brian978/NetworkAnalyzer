<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Controller;

class TypesController extends AbstractController
{
    protected $formParams = array(
        'type' => '\Devices\Form\TypesFrom',
        'object' => '\Devices\Entity\Type',
        'model' => 'Devices\Model\TypesModel',
    );
}