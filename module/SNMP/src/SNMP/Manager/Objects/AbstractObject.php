<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

abstract class AbstractObject
{
    /**
     * @var AbstractObject
     */
    protected $parentObject;

    /**
     * @param AbstractObject $object
     */
    public function __construct(AbstractObject $object)
    {
        $this->parentObject = $object;
    }

    /**
     * @return AbstractObject
     */
    public function getParentObject()
    {
        return $this->parentObject;
    }

    /**
     * Made to avoid multiple setters and getters
     *
     * @param       $name
     * @param array $arguments
     * @return $this
     */
    public function __call($name, $arguments = array())
    {
        if (strpos($name, 'get') !== false) {
            return $this->propertyGet($name);
        } elseif (strpos($name, 'set') !== false) {
            $this->propertySet($name, $arguments);
        }

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function propertyGet($name)
    {
        $name = str_replace('get', '', $name);
        $name = lcfirst($name);

        if (property_exists($this, $name) && $this->$name !== null) {
            return $this->$name;
        }

        return new NullObject();
    }

    /**
     * @param $name
     * @param $arguments
     */
    protected function propertySet($name, $arguments)
    {
        $name = str_replace('set', '', $name);
        $name = lcfirst($name);

        if (property_exists($this, $name)) {
            $this->$name = $arguments[0];
        }
    }
}
