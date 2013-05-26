<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

use Devices\Entity\Device;
use Devices\Entity\Iface;
use Devices\Entity\Type;
use Devices\Form\Fieldset\IfaceType;
use Zend\Stdlib\Hydrator\ClassMethods;

class DevicesModel extends AbstractModel
{
    protected $table = 'devices';

    public function fetch()
    {
        $this->addJoin(
            'device_types',
            'device_types.id = devices.type_id',
            array('typeName' => 'name')
        );
        $this->addJoin(
            'interface_types',
            'interface_types.id = devices.interface_type_id',
            array('interfaceType' => 'name')
        );

        return parent::fetch();
    }

    /**
     * @param \ArrayObject $row
     * @return object
     */
//    protected function processRow(\ArrayObject $row)
//    {
//        $hydrator = new ClassMethods();
//        $data     = $row->getArrayCopy();
//
//        // Device type
//        $data['type'] = array();
//
//        // Interface data
//        $data['interface'] = array();
//
//        // Interface type data
//        $data['interface']['type'] = array();
//
//        foreach ($data as $id => $value) {
//            switch ($id) {
//                case 'typeName':
//                    $data['type']['name'] = $value;
//                    break;
//
//                case 'type_id':
//                    $data['type']['id'] = $value;
//                    break;
//
//                case 'interface_type_id':
//                    $data['interface']['type']['id'] = $value;
//                    break;
//
//                case 'interfaceType':
//                    $data['interface']['type']['name'] = $value;
//                    break;
//
//                case 'ip':
//                    $data['interface']['ip'] = $value;
//                    break;
//            }
//        }
//
//        // Building the required objects for the device object
//        $data['type']              = $hydrator->hydrate($data['type'], new Type());
//        $data['interface']['type'] = $hydrator->hydrate($data['interface']['type'], new Type());
//        $data['interface']         = $hydrator->hydrate($data['interface'], new Iface());
//
//        $device = $hydrator->hydrate($data, new Device());
//
//        return $device;
//    }

    /**
     * This returns the number of affected rows
     *
     * @param \Devices\Entity\Device $object
     *
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data                      = array();
        $data['name']              = $object->getName();
        $data['snmp_version']      = $object->getSnmpVersion();
        $data['snmp_community']    = $object->getSnmpCommunity();
        $data['type_id']           = $object->getType()->getId();
        $data['ip']                = $object->getInterface()->getIp();
        $data['interface_type_id'] = $object->getInterface()->getType()->getId();

        try {
            // If successful will return the number of rows
            $result = $this->insert($data);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * This returns the number of affected rows
     *
     * @param \Devices\Entity\Device $object
     *
     * @return int
     */
    protected function doUpdate($object)
    {
        $data                      = array();
        $data['name']              = $object->getName();
        $data['snmp_version']      = $object->getSnmpVersion();
        $data['snmp_community']    = $object->getSnmpCommunity();
        $data['type_id']           = $object->getType()->getId();
        $data['ip']                = $object->getInterface()->getIp();
        $data['interface_type_id'] = $object->getInterface()->getType()->getId();

        return $this->executeUpdateById($data, $object);
    }
}
