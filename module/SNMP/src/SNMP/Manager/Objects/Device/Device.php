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
     * @param AbstractProcessorObject $contact
     */
    public function setContact(AbstractProcessorObject $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param Iface $interface
     */
    public function attachInterface(Iface $interface)
    {
        $this->interfaces[] = $interface;
    }

    /**
     * @param array $interfaces
     */
    public function setInterfaces($interfaces)
    {
        $this->interfaces = $interfaces;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @param AbstractProcessorObject $location
     */
    public function setLocation(AbstractProcessorObject $location)
    {
        $this->location = $location;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *
     * @param AbstractProcessorObject $uptime
     */
    public function setUptime(AbstractProcessorObject $uptime)
    {
        $this->uptime = $uptime;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getUptime()
    {
        return $this->uptime;
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
