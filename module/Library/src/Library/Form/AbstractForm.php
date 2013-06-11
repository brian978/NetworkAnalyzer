<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Form;

use Library\Form\Fieldset\AbstractFieldset;
use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

abstract class AbstractForm extends Form implements
    TranslatorAwareInterface,
    ServiceLocatorAwareInterface
{
    const MODE_ADD  = 1;
    const MODE_EDIT = 2;

    /**
     * Determines the mode of the form to be able to activate or deactivate certain fields
     *
     * @var int
     */
    public $mode = self::MODE_ADD;

    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param  null|int|string $name    Optional name for the element
     * @param  array           $options Optional options for the element
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setInputFilter(new InputFilter());
    }

    abstract protected function getBaseFieldsetObject();

    /**
     * This function is used to set up the base fieldset object in a primitive way
     * For something more complex use only the getBaseFieldsetObject() method
     *
     * @param AbstractFieldset $object
     *
     * @return AbstractFieldset
     */
    final protected function setupBaseFieldsetObject(AbstractFieldset $object)
    {
        $object->setUseAsBaseFieldset(true);

        if ($this->mode == self::MODE_ADD) {
            $object->setDenyFilters(array('id'));
        }

        if ($object instanceof ServiceLocatorAwareInterface) {
            $object->setServiceLocator($this->serviceLocator);
        }

        if ($object instanceof TranslatorAwareInterface) {
            $object->setTranslator($this->getTranslator());
        }

        $object->loadElements();

        return $object;
    }

    /**
     * Initializes the elements of the form
     *
     * @return $this
     */
    public function loadElements()
    {
        // Adding the elements
        $this->add($this->getBaseFieldsetObject());

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf'
            )
        );

        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Send'
                )
            )
        );

        return $this;
    }

    /**
     * Sets translator to use in helper
     *
     * @param  Translator $translator  [optional] translator.
     *                                 Default is null, which sets no translator.
     * @param  string     $textDomain  [optional] text domain
     *                                 Default is null, which skips setTranslatorTextDomain
     *
     * @return TranslatorAwareInterface
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        $this->translator = $translator;

        // noting to set
        unset($textDomain);

        return $this;
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
     *
     * @return TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled = true)
    {
        // nothing to set
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
     *
     * @return TranslatorAwareInterface
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        // nothing to set
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
        return '';
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
}
