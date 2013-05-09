<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace UI\Library\View\Helpers;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class RenderInputError extends AbstractHelper
{
    public function __invoke(ElementInterface $element)
    {
        $message  = '';
        $messages = $element->getMessages();

        if (!empty($messages))
        {
            $message = $this->view
                ->plugin('partial')
                ->__invoke('partials/form_errors.phtml', array('message' => array_shift($messages)));
        }

        return $message;
    }
}