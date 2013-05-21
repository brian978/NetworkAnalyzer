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
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class Factory extends ZendFormFactory
{
    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;

    protected function getTranslator()
    {
        if (!is_object($this->translator)) {
            $this->translator = $this->getFormElementManager(
            )->getServiceLocator()->get('translator');
        }

        return $this->translator;
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
            $form->setTranslator($this->getTranslator());
        }

        if ($form instanceof ServiceLocatorAwareInterface) {
            $form->setServiceLocator(
                $this->getFormElementManager()->getServiceLocator()
            );
        }

        return $form;
    }
}
