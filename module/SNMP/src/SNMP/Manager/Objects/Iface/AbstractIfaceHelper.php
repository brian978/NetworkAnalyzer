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

abstract class AbstractIfaceHelper extends AbstractProcessorObject
{
    /**
     * @param mixed $data
     * @return $this
     */
    protected function bindToInterfaceObject($data)
    {
        if (is_array($data)) {
            $oidIndex = $this->getOidIndex(key($data));
        } else {
            $oidIndex = $data;
        }

        /** @var $device Device */
        $device    = $this->parentObject;
        $interface = $device->getInterfaceByOidIndex($oidIndex);

        // The default parent object is the Device object, so we need to change it so
        // it points to an Iface object
        $this->parentObject = $interface;

        return $this;
    }
}
