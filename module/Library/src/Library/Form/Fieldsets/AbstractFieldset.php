<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Form\Fieldsets;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class AbstractFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
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

    public function setDenyFilters(array $filters)
    {
        $this->denyFilters = $filters;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
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
     */
    protected function setModel($serviceName)
    {
        $this->model = $this->serviceLocator->get($serviceName);
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
     * Eliminates the filters that are in the deny list
     *
     * @param array $filters
     */
    protected function processDenyFilters(array &$filters)
    {
        // Removing the un-required filters (this is useful when you don't show all the fields)
        foreach($this->denyFilters as $input)
        {
            if(isset($filters[$input]))
            {
                unset($filters[$input]);
            }
        }
    }
}