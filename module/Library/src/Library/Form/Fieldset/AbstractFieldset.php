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
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class AbstractFieldset extends Fieldset implements
    InputFilterProviderInterface,
    ServiceLocatorAwareInterface,
    TranslatorAwareInterface
{
    const MODE_ADMIN = 1;

    /**
     * Depending on this mode the object will add the ID element differently
     *
     * @var int
     */
    public $mode = self::MODE_ADMIN;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;

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

    abstract public function loadElements();

    /**
     * @param AbstractFieldset $fieldset
     * @return AbstractFieldset
     */
    protected function buildFieldset(AbstractFieldset $fieldset)
    {
        $fieldset->setServiceLocator($this->serviceLocator);
        $fieldset->setTranslator($this->translator);

        return $fieldset;
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
     * These are a set of input filters that are used by most forms
     *
     * @return array
     */
    protected function getGenericInputFilterSpecs()
    {
        $translator = $this->serviceLocator->get('translator');

        $filters = array(
            'id' => array(
                'validators' => array(
                    array(
                        'name' => 'greater_than',
                        'options' => array(
                            'min' => 0,
                            'message' => $translator->translate('You must select a value')
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

    /**
     * Sets translator to use in helper
     *
     * @param  Translator $translator  [optional] translator.
     *                                 Default is null, which sets no translator.
     * @param  string     $textDomain  [optional] text domain
     *                                 Default is null, which skips setTranslatorTextDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        unset($textDomain);

        $this->translator = $translator;

        return $translator;
    }

    /**
     * Returns translator used in object
     *
     * @return Translator|null
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Checks if the object has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return is_object($this->translator);
    }

    /**
     * Sets whether translator is enabled and should be used
     *
     * @param  bool $enabled [optional] whether translator should be used.
     *                       Default is true.
     * @return TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled = true)
    {
        unset($enabled);

        return $this->translator;
    }

    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled()
    {
        return true;
    }

    /**
     * Set translation text domain
     *
     * @param  string $textDomain
     * @return TranslatorAwareInterface
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        unset($textDomain);

        return $this->translator;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return 'default';
    }
}
