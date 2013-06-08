<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Helper;

use Devices\Entity\Iface;
use Devices\Entity\Type;
use Zend\Stdlib\Hydrator\ClassMethods;

class DeviceData
{
    /**
     * @var \ArrayAccess
     */
    protected $object;

    /**
     * @param \ArrayAccess $object
     */
    public function __construct(\ArrayAccess $object = null)
    {
        $this->setData($object);
    }

    /**
     * @param \ArrayAccess $object
     * @return $this
     */
    public function setData(\ArrayAccess $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @param bool $createObjects
     * @return array
     */
    public function extract($createObjects = false)
    {
        $deviceData = array(
            'id' => $this->object->id,
            'name' => $this->object->name,
            'snmpVersion' => $this->object->snmp_version,
            'snmpCommunity' => $this->object->snmp_community,
            'type' => array(
                'id' => $this->object->type_id
            ),
            'interface' => array(
                'ip' => $this->object->ip,
                'type' => array(
                    'id' => $this->object->interface_type_id
                )
            )
        );

        if ($createObjects === true) {

            $hydrator = new ClassMethods();

            $deviceData['type']              = $hydrator->hydrate($deviceData['type'], new Type());
            $deviceData['interface']['type'] = $hydrator->hydrate($deviceData['interface']['type'], new Type());
            $deviceData['interface']         = $hydrator->hydrate($deviceData['interface'], new Iface());
        }

        return $deviceData;
    }
}
