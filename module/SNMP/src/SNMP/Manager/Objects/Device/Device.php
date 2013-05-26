<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Device;

use SNMP\Manager\ObjectManager;
use SNMP\Manager\Objects\AbstractObject;
use SNMP\Manager\Objects\AbstractProcessorObject;
use SNMP\Manager\Objects\Iface\Iface;

/**
 * Class Device
 *
 * @package SNMP\Manager\Objects
 */
class Device extends AbstractObject
{
    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $uptime;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $contact;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $location;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $name;

    /**
     * @var array
     */
    protected $interfaces;

    /**
     * The device is the master object so it has the ObjectManager as it's parent
     */
    public function __construct(ObjectManager $object)
    {
        $this->parentObject = $object;
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
            $name = str_replace('get', '', $name);
            $name = lcfirst($name);

            if (property_exists($this, $name)) {
                return $this->$name;
            }
        } elseif (strpos($name, 'set') !== false) {
            $name = str_replace('set', '', $name);
            $name = lcfirst($name);

            if (property_exists($this, $name)) {
                $this->$name = $arguments[0];
            }
        }

        return $this;
    }

    /**
     * @param Iface $interface
     * @param int   $oidIndex
     */
    public function attachInterface(Iface $interface, $oidIndex = null)
    {
        if ($oidIndex === null) {
            $oidIndex = $interface->getOidIndex();
        }

        $this->interfaces[$oidIndex] = $interface;
    }

    /**
     * @param $oidIndex
     * @return null|Iface
     */
    public function getInterfaceByOidIndex($oidIndex)
    {
        $iface = null;

        if (isset($this->interfaces[$oidIndex])) {
            $iface = $this->interfaces[$oidIndex];
        }

        return $iface;
    }
}
