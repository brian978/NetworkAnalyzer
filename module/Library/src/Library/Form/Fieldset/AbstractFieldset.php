<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class AbstractFieldset extends Fieldset implements
    InputFilterProviderInterface,
    ServiceLocatorAwareInterface
{
    const MODE_SELECT = 1;
    const MODE_ADMIN  = 2;

    /**
     * Depending on this mode the object will add the ID element differently
     *
     * @var int
     */
    public $mode = self::MODE_ADMIN;

    /**
     * This is used when the setModel() method is called twice (like when extending a form)
     *
     * @var boolean
     */
    protected $lockModel = false;

    /**
     * @var \Library\Model\AbstractDbModel
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelName = '';

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $denyFilters = array();

    /**
     * @param string $name
     * @param array  $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
    }

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setDenyFilters(array $filters)
    {
        $this->denyFilters = array_merge($this->denyFilters, $filters);

        return $this;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return \Devices\Model\AbstractModel
     */
    protected function getModel()
    {
        if (!is_object($this->model)) {
            $this->setModel($this->modelName);
        }

        return $this->model;
    }

    /**
     * Initialized the model required for the database
     *
     * @param      $serviceName
     * @param bool $lockModel
     *
     * @return $this
     */
    protected function setModel($serviceName, $lockModel = false)
    {
        // By default it can be changed any time so we only need to set it when it's true
        if ($lockModel === true) {
            $this->lockModel = $lockModel;
        }

        if (!is_object($this->model) || $this->lockModel === false) {
            $this->model = $this->serviceLocator->get($serviceName);
        }

        return $this;
    }

    /**
     * Builds an array of options for the select box
     *
     * @return array
     */
    protected function getValueOptions()
    {
        $options = $this->getModel()->fetch();

        foreach ($options as $value => $row) {
            $options[$value] = $row['name'];
        }

        $options = array_merge(
            array(
                0 => '...'
            ),
            $options
        );

        return $options;
    }

    /**
     *
     * @param string $label
     *
     * @return array
     */
    protected function getIdElement($label)
    {
        if ($this->mode == self::MODE_SELECT) {
            $element = $this->getSelectId($label);
        } else {
            $element = $this->getHiddenId();
        }

        return $element;
    }

    /**
     *
     * @return array
     */
    protected function getHiddenId()
    {
        return array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'value' => 0
            )
        );
    }

    /**
     * @param $label
     *
     * @return array
     */
    protected function getSelectId($label)
    {
        return array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'id',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'form_row'
                ),
                'value_options' => $this->getValueOptions()
            ),
            'attributes' => array(
                'required' => true
            )
        );
    }


    /**
     *
     * @return array
     */
    protected function getGenericInputFilterSpecs()
    {
        $filters = array(
            'id' => array(
                'validators' => array(
                    array(
                        'name' => 'greater_than',
                        'options' => array(
                            'min' => 0,
                            'message' => 'You must select a value'
                        )
                    )
                )
            ),
            'name' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 2
                        )
                    )
                )
            )
        );

        return $filters;
    }

    /**
     * Eliminates the filters that are in the deny list
     *
     * @param array $filters
     *
     * @return array
     */
    protected function processDenyFilters(array $filters)
    {
        // Removing the un-required filters (this is useful when you don't show all the fields)
        foreach ($this->denyFilters as $input) {
            if (isset($filters[$input])) {
                unset($filters[$input]);
            }
        }

        return $filters;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->processDenyFilters($this->getGenericInputFilterSpecs());
    }
}
