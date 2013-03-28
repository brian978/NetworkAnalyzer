<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace UI\Library\Navigation;

use Zend\Navigation\Navigation as ZendNav;

class Navigation extends ZendNav
{
    public function findbyLabel($value, $all = false)
    {
        return $this->findBy('label', $value, $all);
    }
}