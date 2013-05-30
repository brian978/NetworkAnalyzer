<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

use SNMP\Manager\Objects\Device\Device;

abstract class AbstractProcessorObject extends AbstractObject implements ObjectProcessorInterface
{
    /**
     * Array of data (used mostly by the processor objects)
     *
     * @var array
     */
    protected $data;

    /**
     * Used to process a single array entry
     *
     * @param array $data
     * @return mixed
     */
    abstract public function process(array $data);

    /**
     * @param array $data
     * @return string
     */
    protected function processStringData(array $data)
    {
        $string = trim(str_replace('STRING: ', '', current($data)));
        $string = str_replace('"', '', $string);

        return $string;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->get();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->get());
    }

    /**
     * @param $oid
     * @return mixed
     */
    protected function getOidIndex($oid)
    {
        $pieces = explode('.', $oid);

        return array_pop($pieces);
    }

    /**
     * @param mixed $data
     * @return $this
     */
    protected function bindToInterfaceObject($data)
    {
        if (is_array($data)) {
            $oidIndex = $this->getOidIndex(key($data));
        } else {
            $oidIndex = $data;
        }

        /** @var $device Device */
        $device    = $this->parentObject;
        $interface = $device->getInterfaceByOidIndex($oidIndex);

        // The default parent object is the Device object, so we need to change it so
        // it points to an Iface object
        $this->parentObject = $interface;

        return $this;
    }
}
