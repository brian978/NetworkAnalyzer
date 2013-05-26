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
}
