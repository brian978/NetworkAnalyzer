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
     * @var int
     */
    protected $oidIndex;

    /**
     * Made to avoid multiple setters and getters
     *
     * @param       $name
     * @param array $arguments
     * @return $this
     */
    public function __call($name, $arguments = array())
    {
        if (strpos($name, 'get') !== false) {
            $name = str_replace('get', '', $name);
            $name = lcfirst($name);

            if (property_exists($this, $name)) {
                return $this->$name;
            }
        } elseif (strpos($name, 'set') !== false) {
            $name = str_replace('set', '', $name);
            $name = lcfirst($name);

            if (property_exists($this, $name)) {
                $this->$name = $arguments[0];
            }
        }

        return $this;
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
