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

class InterfaceTypesController extends AbstractController
{
    /**
     * These parameters are used to create the required form
     *
     * @var array
     */
    protected $formSpecs = array(
        'type' => '\Devices\Form\InterfaceTypesForm',
        'object' => '\Devices\Entity\Type',
        'model' => 'Devices\Model\InterfaceTypesModel',
        'dataKey' => 'type',
    );
}
