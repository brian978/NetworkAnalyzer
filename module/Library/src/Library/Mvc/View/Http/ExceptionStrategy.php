<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Mvc\View\Http;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\View\Http\ExceptionStrategy as ZendExceptionStrategy;

class ExceptionStrategy extends ZendExceptionStrategy
{
    public function prepareExceptionViewModel(MvcEvent $e)
    {
        echo 'test';
    }
}