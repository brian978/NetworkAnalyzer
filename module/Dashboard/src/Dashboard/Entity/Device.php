<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Dashboard\Entity;

class Device
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
}