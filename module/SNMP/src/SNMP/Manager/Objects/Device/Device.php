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
use SNMP\Manager\Objects\Tcp\Connection;

/**
 * Class Device
 *
 * @package SNMP\Manager\Objects
 * @method setDeviceEntity(\Devices\Entity\Device $device)
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
     * @var array
     */
    protected $tcpConnections;

    /**
     * @var \Devices\Entity\Device
     */
    protected $deviceEntity;

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

            $interfaces[] = $subInterface;

            if ($subInterface->hasInterfaces()) {
                $interfaces = array_merge($interfaces, $this->getSubInterfaces($subInterface));
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

            $interfaces[] = $interface;

            if ($interface->hasInterfaces()) {
                $interfaces = array_merge($interfaces, $this->getSubInterfaces($interface));
            }
        }

        return $interfaces;
    }

    /**
     * @param Iface $interface
     * @param int   $oidIndex
     * @return $this
     */
    public function attachInterface(Iface $interface, $oidIndex = 0)
    {
        if ($oidIndex === 0) {
            $oidIndex = $interface->getOidIndex();
        }

        $this->interfaces[$oidIndex] = $interface;

        return $this;
    }

    /**
     * @param Connection $connection
     * @return $this
     */
    public function attachTcpConnection(Connection $connection)
    {
        $this->tcpConnections[] = $connection;

        return $this;
    }

    /**
     * @return array
     */
    public function getTcpConnections()
    {
        return $this->tcpConnections;
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

    /**
     * Returns an array 1 interface by IP
     *
     * @param $ipAddress
     * @return array|null
     */
    public function getInterfaceByIP($ipAddress)
    {
        $interface = null;

        foreach ($this->interfaces as $interfaceObject) {
            if ($interfaceObject->getIp()->get() == $ipAddress) {
                $interface = $interfaceObject;
            }
        }

        return $interface;
    }
}
