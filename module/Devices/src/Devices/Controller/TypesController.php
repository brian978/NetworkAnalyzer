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

class TypesController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs
        = array(
            'type' => '\Devices\Form\TypesFrom',
            'object' => '\Devices\Entity\Type',
            'model' => 'Devices\Model\TypesModel',
            'dataKey' => 'type',
        );
}