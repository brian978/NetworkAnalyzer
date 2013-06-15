<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\View\Helper;

use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\View\Helper\HelperInterface;
use Zend\View\Renderer\RendererInterface as Renderer;

class UnitConverter implements HelperInterface, TranslatorAwareInterface
{
    /**
     * @var \Zend\View\Model\ViewModel
     */
    protected $view = null;

    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator = null;

    /**
     * Set the View object
     *
     * @param  Renderer $view
     * @return HelperInterface
     */
    public function setView(Renderer $view)
    {
        $this->view = $view;
    }

    /**
     * Get the View object
     *
     * @return Renderer
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $args   = func_get_args();
        $amount = $args[0];
        $type   = 0;

        $unitType = array(
            0 => $this->translator->translate('B'),
            1 => $this->translator->translate('KB'),
            2 => $this->translator->translate('MB'),
            3 => $this->translator->translate('GB'),
            4 => $this->translator->translate('TB'),
            5 => $this->translator->translate('PB'),
        );

        // Converting from B/s to the highest unit available up to TB/s speeds
        while (floor($amount) > 1024 && $type < 5) {
            $amount /= 1024;
            $type++;
        }

        return round($amount, 2) . ' ' . $unitType[$type];
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
        $this->translator = $translator;

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
     * @return TranslatorAwareInterface
     */
    public function setTranslatorEnabled($enabled = true)
    {
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
        return null;
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
}
