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
     * Returns the sub interfaces of the given interface
     *
     * This is recursive
     *
     * @param $interface
     * @return array
     */
    protected function getSubInterfaces($interface)
    {
        $interfaces = array();

        foreach ($interface->getInterfaces() as $subInterface) {
            if ($subInterface->hasInterfaces()) {
                $interfaces = array_merge($interfaces, $this->getSubInterfaces($subInterface));
            } else {
                $interfaces[] = $subInterface;
            }
        }

        return $interfaces;
    }

    /**
     * @return array
     */
    public function getInterfaces()
    {
        $interfaces = array();

        foreach ($this->interfaces as $interface) {
            if ($interface->hasInterfaces()) {
                $interfaces = array_merge($interfaces, $this->getSubInterfaces($interface));
            } else {
                $interfaces[] = $interface;
            }
        }

        return $interfaces;
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

    /**
     * Returns an array with 1 or more interfaces by name
     *
     * @param $name
     * @return array
     */
    public function getInterfacesByName($name)
    {
        $interfaces = array();

        foreach ($this->interfaces as $oidIndex => $interface) {
            if ($interface->getName()->get() == $name) {
                $interfaces[$oidIndex] = $interface;
            }
        }

        return $interfaces;
    }
}
