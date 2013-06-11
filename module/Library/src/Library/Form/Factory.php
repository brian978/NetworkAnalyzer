<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Form;

use Zend\Form\Factory as ZendFormFactory;
use Zend\Form\FormElementManager;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class Factory extends ZendFormFactory
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param FormElementManager $formElementManager
     */
    public function __construct(FormElementManager $formElementManager = null)
    {
        parent::__construct($formElementManager);

        $this->serviceLocator = $this->getFormElementManager()->getServiceLocator();
    }

    /**
     * Create an element, fieldset, or form
     *
     * Introspects the 'type' key of the provided $spec, and determines what
     * type is being requested; if none is provided, assumes the spec
     * represents simply an element.
     *
     * @param  array|Traversable $spec
     *
     * @return ElementInterface
     */
    public function create($spec)
    {
        $form = parent::create($spec);

        if ($form instanceof TranslatorAwareInterface) {
            $form->setTranslator($this->serviceLocator->get('translator'));
        }

        if ($form instanceof ServiceLocatorAwareInterface) {
            $form->setServiceLocator($this->serviceLocator);
        }

        return $form;
    }
}
