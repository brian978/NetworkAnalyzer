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

abstract class AbstractFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    /**
     * This is used when the setModel() method is called twice (like when extending a form)
     *
     * @var boolean
     */
    protected $lockModel = false;

    /**
     * @var AbstractModel
     */
    protected $model;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $denyFilters = array();

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setDenyFilters(array $filters)
    {
        $this->denyFilters = $filters;

        return $this;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
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
     * Initialized the model required for the database
     *
     * @param      $serviceName
     * @param bool $lockModel
     * @return $this
     */
    protected function setModel($serviceName, $lockModel = false)
    {
        // By default it can be changed any time so we only need to set it when it's true
        if($lockModel === true)
        {
            $this->lockModel = $lockModel;
        }

        if(!is_object($this->model) || $this->lockModel === false)
        {
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
        $options = $this->model->fetch();

        foreach ($options as $value => $row)
        {
            $options[$value] = $row['name'];
        }

        $options = array_merge(array(
            0 => '...'
        ), $options);

        return $options;
    }

    /**
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
                            'min' => 3
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
     * @return array
     */
    protected function processDenyFilters(array $filters)
    {
        // Removing the un-required filters (this is useful when you don't show all the fields)
        foreach($this->denyFilters as $input)
        {
            if(isset($filters[$input]))
            {
                unset($filters[$input]);
            }
        }

        return $filters;
    }
}