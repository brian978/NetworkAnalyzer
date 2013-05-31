<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Entity;

class Log extends AbstractEntity
{
    /**
     * @var int
     */
    protected $time;

    /**
     * @var int
     */
    protected $oidIndex;

    /**
     * @var string
     */
    protected $interfaceName;

    /**
     * @var int
     */
    protected $octetsIn;

    /**
     * @var int
     */
    protected $octetsOut;

    /**
     * @var string
     */
    protected $mac;

    /**
     * @var Device
     */
    protected $device;

    /**
     * @var string
     */
    protected $uptime;

    /**
     * @param string $uptime
     */
    public function setUptime($uptime)
    {
        $this->uptime = $uptime;
    }

    /**
     * @return string
     */
    public function getUptime()
    {
        return $this->uptime;
    }

    /**
     * @param \Devices\Entity\Device $device
     */
    public function setDevice($device)
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

    /**
     * @param string $interfaceName
     */
    public function setInterfaceName($interfaceName)
    {
        $this->interfaceName = $interfaceName;
    }

    /**
     * @return string
     */
    public function getInterfaceName()
    {
        return $this->interfaceName;
    }

    /**
     * @param string $mac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    /**
     * @return string
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param int $octetsIn
     */
    public function setOctetsIn($octetsIn)
    {
        $this->octetsIn = $octetsIn;
    }

    /**
     * @return int
     */
    public function getOctetsIn()
    {
        return $this->octetsIn;
    }

    /**
     * @param int $octetsOut
     */
    public function setOctetsOut($octetsOut)
    {
        $this->octetsOut = $octetsOut;
    }

    /**
     * @return int
     */
    public function getOctetsOut()
    {
        return $this->octetsOut;
    }

    /**
     * @param int $oidIndex
     */
    public function setOidIndex($oidIndex)
    {
        $this->oidIndex = $oidIndex;
    }

    /**
     * @return int
     */
    public function getOidIndex()
    {
        return $this->oidIndex;
    }

    /**
     * @param int $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }
}
