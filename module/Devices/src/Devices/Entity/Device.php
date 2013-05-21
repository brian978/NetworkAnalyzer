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
    protected $snmpVersion;

    /**
     * @var string
     */
    protected $snmpCommunity;

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
