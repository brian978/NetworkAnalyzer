<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Entity;

class Device extends AbstractEntity
{
    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Type
     */
    protected $type;

    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setType(Type $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}