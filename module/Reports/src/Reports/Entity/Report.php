<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Entity;

class Report
{

    protected $data = array();

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

        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @param $arguments
     */
    protected function propertySet($name, $arguments)
    {
        $name = str_replace('set', '', $name);
        $name = lcfirst($name);

        $this->data[$name] = array_shift($arguments);
    }
}
