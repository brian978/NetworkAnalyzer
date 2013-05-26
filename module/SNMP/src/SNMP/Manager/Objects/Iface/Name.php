<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Iface;

use SNMP\Manager\Objects\AbstractProcessorObject;

/**
 * Class Name
 *
 * @package SNMP\Manager\Objects\Iface
 */
class Name extends AbstractProcessorObject
{
    /**
     * @param array $data
     * @return $this|mixed
     */
    protected function processSingle(array $data)
    {
        $this->bindToInterfaceObject($data);
        $this->parentObject->setName($this);

        $this->data = trim(str_replace('STRING: ', '', current($data)));

        return $this;
    }
}
