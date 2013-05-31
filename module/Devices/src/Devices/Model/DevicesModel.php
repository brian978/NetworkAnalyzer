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
