<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Entity;

class Device extends AbstractEntity
{
    /**
     * @var Location
     */
    protected $location;

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var string
     */
    protected $snmp_version;

    /**
     * @var string
     */
    protected $snmp_community;

    /**
     * @param string $snmp_community
     */
    public function setSnmp_Community($snmp_community)
    {
        $this->snmp_community = $snmp_community;
    }

    /**
     * @return string
     */
    public function getSnmpCommunity()
    {
        return $this->snmp_community;
    }

    /**
     * @param string $snmp_version
     */
    public function setSnmp_Version($snmp_version)
    {
        $this->snmp_version = $snmp_version;
    }

    /**
     * @return string
     */
    public function getSnmpVersion()
    {
        return $this->snmp_version;
    }

    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setType(Type $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}