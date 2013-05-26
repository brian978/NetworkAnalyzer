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
    abstract protected function processSingle(array $data);

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
     * The method is used to process a string of data provided by the ObjectManager
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        if (count($data) > 1) {
            foreach ($data as $oid => $value) {
                $this->processSingle(array($oid => $value));
            }
        } else {
            $this->processSingle($data);
        }

        return $this;
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
     * @param array $data
     * @return $this
     */
    protected function bindToInterfaceObject($data)
    {
        if ($this->parentObject instanceof Device) {
            /** @var $device Device */
            $device    = $this->parentObject;
            $interface = $device->getInterfaceByOidIndex($this->getOidIndex(key($data)));

            // The default parent object is the Device object, so we need to change it so
            // it points to an Iface object
            $this->parentObject = $interface;
        }

        return $this;
    }
}
