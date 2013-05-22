<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

abstract class AbstractObject extends \ArrayObject
{
    /**
     * The method is used to process a string of data provided by the ObjectManager
     *
     * @param mixed $data
     * @return mixed
     */
    abstract public function process($data);

    /**
     * Sets a set of information for the object
     *
     * @param $info
     * @return $this
     */
    protected function setInfo($info)
    {
        parent::offsetSet('info', $info);

        return $this;
    }
}