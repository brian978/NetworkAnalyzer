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
use Reports\Form\Fieldset\DevicesUptime as DevicesUptimeFieldset;

class DevicesUptime extends AbstractForm implements DbModelAwareInterface
{
    /**
     * @var \Library\Model\AbstractDbModel;
     */
    protected $model;

    public function __construct()
    {
        parent::__construct('devices_uptime_form');

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
        $fieldset = new DevicesUptimeFieldset();
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
