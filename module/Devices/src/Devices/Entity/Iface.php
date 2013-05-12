<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Entity;

use Library\Entity\AbstractEntity;

class Iface extends AbstractEntity
{
    protected $name;
    protected $mac;
    protected $ip;

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var Device
     */
    protected $device;

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    public function getMac()
    {
        return $this->mac;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Devices\Entity\Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return \Devices\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Devices\Entity\Device $device
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;
    }

    /**
     * @return \Devices\Entity\Device
     */
    public function getDevice()
    {
        return $this->device;
    }
}