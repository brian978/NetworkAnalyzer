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
     * @var float
     */
    protected $bandwidthIn;

    /**
     * @var float
     */
    protected $bandwidthOut;

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
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $netmask;

    /**
     * @var string
     */
    protected $discontinuityCounter;

    /**
     * @param string $discontinuityCounter
     */
    public function setDiscontinuityCounter($discontinuityCounter)
    {
        $this->discontinuityCounter = $discontinuityCounter;
    }

    /**
     * @return string
     */
    public function getDiscontinuityCounter()
    {
        return $this->discontinuityCounter;
    }

    /**
     * @param string $netmask
     */
    public function setNetmask($netmask)
    {
        $this->netmask = $netmask;
    }

    /**
     * @return string
     */
    public function getNetmask()
    {
        return $this->netmask;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

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

    /**
     * @param float $bandwidthIn
     */
    public function setBandwidthIn($bandwidthIn)
    {
        $this->bandwidthIn = $bandwidthIn;
    }

    /**
     * @return float
     */
    public function getBandwidthIn()
    {
        return $this->bandwidthIn;
    }

    /**
     * @param float $bandwidthOut
     */
    public function setBandwidthOut($bandwidthOut)
    {
        $this->bandwidthOut = $bandwidthOut;
    }

    /**
     * @return float
     */
    public function getBandwidthOut()
    {
        return $this->bandwidthOut;
    }
}
