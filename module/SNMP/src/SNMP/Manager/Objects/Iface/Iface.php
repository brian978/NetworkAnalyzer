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
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $in;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $out;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $adminStatus;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $status;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $queueLength;

    /**
     * @var \SNMP\Manager\Objects\AbstractProcessorObject
     */
    protected $speed;

    /**
     * @var int
     */
    protected $oidIndex;

    /**
     * @var array
     */
    protected $interfaces = array();

    /**
     * @var float
     */
    protected $bandwidthIn = 0;

    /**
     * Determines if the bandwidth is in KB/s or in MB/s
     *
     * 0 - B/s
     * 1 - KB/s
     * 2 - MB/s
     *
     * @var int
     */
    protected $bandwidthInType = 0;

    /**
     * @var float
     */
    protected $bandwidthOut = 0;

    /**
     * Determines if the bandwidth is in KB/s or in MB/s
     *
     * 0 - B/s
     * 1 - KB/s
     * 2 - MB/s
     *
     * @var int
     */
    protected $bandwidthOutType = 0;

    /**
     * @param $name
     * @param $arguments
     */
    protected function propertySet($name, $arguments)
    {
        // This will be passed to child interfaces
        $callName = $name;

        $name = str_replace('set', '', $name);
        $name = lcfirst($name);

        if (property_exists($this, $name)) {
            $this->$name = $arguments[0];

            // Setting the data for the other sub-interfaces as well
            if ($name != 'ip' && $this->hasInterfaces()) {
                foreach ($this->interfaces as $interface) {
                    call_user_func_array(array($interface, $callName), $arguments);
                }
            }
        }
    }

    /**
     * @param Iface $interface
     */
    public function attachInterface(Iface $interface)
    {
        $this->interfaces[] = $interface;
    }

    /**
     * @param $index
     * @return mixed
     */
    public function getInterface($index)
    {
        $iface = null;

        if (isset($this->interfaces[$index])) {

            /** @var $iface Iface */
            $iface = $this->interfaces[$index];
        }

        return $iface;
    }

    /**
     * Checks if the interface has other sub-interfaces
     *
     * @return bool
     */
    public function hasInterfaces()
    {
        return (bool)count($this->interfaces);
    }

    /**
     * @return array
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @param string $oidValue
     * @return int
     */
    public static function extractOidIndex($oidValue)
    {
        return intval(trim(str_replace('INTEGER: ', '', $oidValue)));
    }
}
