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
use SNMP\Manager\Objects\Device\Device;

/**
 * Class Mac
 *
 * @package SNMP\Manager\Objects\Iface
 */
class Mac extends AbstractProcessorObject
{
    /**
     * @param array $data
     * @return $this|mixed
     */
    public function process(array $data)
    {
        $this->bindToInterfaceObject($data);
        $this->parentObject->setMac($this);

        $this->data = trim(str_replace('Hex-STRING: ', '', current($data)));

        return $this;
    }
}
