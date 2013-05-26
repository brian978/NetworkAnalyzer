<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Iface;

use SNMP\Manager\Objects\AbstractObject;
use SNMP\Manager\Objects\AbstractProcessorObject;
use SNMP\Manager\Objects\Device\Device;

/**
 * Class Iface
 *
 * @package SNMP\Manager\Objects\Iface
 */
class Iface extends AbstractObject
{
    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $ip;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $name;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $mac;

    /**
     * @var int
     */
    protected $oidIndex;

    /**
     * @param Device $object
     */
    public function __construct(Device $object)
    {
        parent::__construct($object);

        $object->attachInterface($this);
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
     * @param AbstractProcessorObject $ip
     */
    public function setIp(AbstractProcessorObject $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param AbstractProcessorObject $name
     */
    public function setName(AbstractProcessorObject $name)
    {
        $this->name = $name;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \SNMP\Manager\Objects\AbstractProcessorObject $mac
     */
    public function setMac(AbstractProcessorObject $mac)
    {
        $this->mac = $mac;
    }

    /**
     * @return \SNMP\Manager\Objects\AbstractProcessorObject
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param string $oid
     * @return int
     */
    public static function extractOidIndex($oid)
    {
        return intval(trim(str_replace('INTEGER: ', '', $oid)));
    }
}
