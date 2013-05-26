<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Entity;

class Iface extends AbstractEntity
{
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
}
