<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\View\Helpers;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class RenderInputError extends AbstractHelper
{
    public function __invoke(ElementInterface $element)
    {
        $message  = '';
        $messages = $element->getMessages();

        if (!empty($messages)) {
            $message = $this->view
                ->plugin('partial')
                ->__invoke('partials/form_errors.phtml', array('message' => $this->getMessageString($messages)));
        }

        return $message;
    }

    protected function getMessageString($messages)
    {
        if (is_array($messages)) {
            $messages = array_shift($messages);

            if (is_array($messages)) {
                return $this->getMessageString($messages);
            }
        }

        return $messages;
    }
}