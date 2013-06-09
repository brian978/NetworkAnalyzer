<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Entity;

use SNMP\Model\Entity\EntityInterface;

class Device extends AbstractEntity implements EntityInterface
{
    /**
     * @var Type
     */
    protected $type;

    /**
     * @var string
     */
    protected $snmpVersion;

    /**
     * @var string
     */
    protected $snmpCommunity;

    /**
     * @var \Devices\Entity\Iface
     */
    protected $interface;

    /**
     * @var string
     */
    protected $location;

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $snmpCommunity
     */
    public function setSnmpCommunity($snmpCommunity)
    {
        $this->snmpCommunity = $snmpCommunity;
    }

    /**
     * @return string
     */
    public function getSnmpCommunity()
    {
        return $this->snmpCommunity;
    }

    /**
     * @param string $snmpVersion
     */
    public function setSnmpVersion($snmpVersion)
    {
        $this->snmpVersion = $snmpVersion;
    }

    /**
     * @return string
     */
    public function getSnmpVersion()
    {
        return $this->snmpVersion;
    }

    /**
     * @param Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Devices\Entity\Iface $interface
     */
    public function setInterface(Iface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * @return \Devices\Entity\Iface
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        $hostname = '';

        if (is_object($this->interface)) {
            $hostname = $this->interface->getIp();
        }

        return $hostname;
    }
}
