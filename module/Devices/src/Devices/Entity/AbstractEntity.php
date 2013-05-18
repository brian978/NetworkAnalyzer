<?php
/**
* NetworkAnalyzer
*
* @link      https://github.com/brian978/NetworkAnalyzer
* @copyright Copyright (c) 2013
* @license   Creative Commons Attribution-ShareAlike 3.0
*/

namespace Devices\Entity;

use Library\Entity\AbstractEntity as LibraryAbstractEntity;

class AbstractEntity extends LibraryAbstractEntity
{
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}