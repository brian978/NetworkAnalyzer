<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Sniffer\Object\Traffic;

use Devices\Entity\Device as DeviceEntity;
use Library\Entity\EntityInterface;
use SNMP\Manager\Objects\Iface\Iface;

/**
 * Class Connection
 *
 * @package Poller\Object\Traffic
 * @property $type
 * @property $srcIp
 * @property $dstIp
 * @property $srcPort
 * @property $dstPort
 */
class Connection implements EntityInterface
{
    /**
     * This is used to mark a connection that is not supported (e.g. not TCP/UDP)
     *
     * @var bool
     */
    protected $valid = true;

    /**
     * The type of the connection (TCP/UDP)
     *
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $srcIp;

    /**
     * @var string
     */
    protected $dstIp;

    /**
     * @var string
     */
    protected $srcPort;

    /**
     * @var string
     */
    protected $dstPort;

    /**
     * @var DeviceEntity
     */
    protected $deviceEntity;

    /**
     * @var Iface
     */
    protected $interface;

    /**
     * @param string $connection
     */
    public function __construct($connection = '')
    {
        if ((stripos($connection, ': tcp') !== false
            || stripos($connection, ': udp') !== false)
            && strpos($connection, 'IP') !== false
        ) {
            $this->process($connection);
        } elseif ($connection !== '') {
            $this->valid = false;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return 0;
    }

    /**
     * @param $connection
     */
    protected function process($connection)
    {
        $start = strpos($connection, 'IP');
        $stop  = stripos($connection, ': tcp');

        // Setting the type as TCP for now
        $this->type = 'TCP';

        if ($stop === false) {
            $stop       = stripos($connection, ': udp');
            $this->type = 'UDP';
        }

        $connection = substr($connection, $start, $stop - $start);
        $pieces     = explode(' ', $connection);

        $this->extractIpPort($pieces[1], 'src');
        $this->extractIpPort($pieces[3], 'dst');
    }

    /**
     * @param string $data
     * @param        $type
     */
    protected function extractIpPort($data, $type)
    {
        $this->{$type . 'Ip'}   = substr($data, 0, strrpos($data, '.'));
        $this->{$type . 'Port'} = substr($data, strrpos($data, '.') + 1);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $value = null;

        if (property_exists($this, $name)) {
            $value = $this->$name;
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @param string $dstIp
     */
    public function setDstIp($dstIp)
    {
        $this->dstIp = $dstIp;
    }

    /**
     * @return string
     */
    public function getDstIp()
    {
        return $this->dstIp;
    }

    /**
     * @param string $dstPort
     */
    public function setDstPort($dstPort)
    {
        $this->dstPort = $dstPort;
    }

    /**
     * @return string
     */
    public function getDstPort()
    {
        return $this->dstPort;
    }

    /**
     * @param string $srcIp
     */
    public function setSrcIp($srcIp)
    {
        $this->srcIp = $srcIp;
    }

    /**
     * @return string
     */
    public function getSrcIp()
    {
        return $this->srcIp;
    }

    /**
     * @param string $srcPort
     */
    public function setSrcPort($srcPort)
    {
        $this->srcPort = $srcPort;
    }

    /**
     * @return string
     */
    public function getSrcPort()
    {
        return $this->srcPort;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param DeviceEntity $deviceEntity
     */
    public function setDeviceEntity(DeviceEntity $deviceEntity)
    {
        $this->deviceEntity = $deviceEntity;
    }

    /**
     * @return \Devices\Entity\Device
     */
    public function getDeviceEntity()
    {
        return $this->deviceEntity;
    }

    /**
     * @param \SNMP\Manager\Objects\Iface\Iface $interface
     */
    public function setInterface(Iface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * @return \SNMP\Manager\Objects\Iface\Iface
     */
    public function getInterface()
    {
        return $this->interface;
    }
}
