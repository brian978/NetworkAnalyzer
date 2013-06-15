<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Form;

use Library\Form\AbstractForm;
use Library\Model\AbstractDbModel;
use Library\Model\DbModelAwareInterface;
use Reports\Form\Fieldset\InterfacesTraffic as InterfacesTrafficFieldset;

class InterfacesTraffic extends AbstractForm implements DbModelAwareInterface
{
    /**
     * @var \Library\Model\AbstractDbModel;
     */
    protected $model;

    public function __construct()
    {
        parent::__construct('interface_bandwidth_form');

        $this->setAttributes(
            array(
                'class' => 'input_form'
            )
        );
    }

    /**
     * @return \Library\Form\Fieldset\AbstractFieldset
     */
    protected function getBaseFieldsetObject()
    {
        $fieldset = new InterfacesTrafficFieldset();
        $fieldset->setModel($this->model);
        $fieldset->setDenyFilters(array('id', 'name'));

        return $this->setupBaseFieldsetObject($fieldset);
    }

    /**
     * @param AbstractDbModel $model
     * @return $this
     */
    public function setModel(AbstractDbModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return AbstractDbModel
     */
    public function getModel()
    {
        $this->model;
    }
}
