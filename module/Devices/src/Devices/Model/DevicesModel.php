<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

use Library\Model\AbstractDbModel;

class DevicesModel extends AbstractModel
{
    protected $table = 'devices';

    public function fetch()
    {
        $this->addJoin(
            'locations',
            'locations.id = devices.location_id',
            array('locationName' => 'name')
        );
        $this->addJoin(
            'device_types',
            'device_types.id = devices.type_id',
            array('typeName' => 'name')
        );
        $this->addJoin(
            'interfaces',
            'interfaces.device_id = devices.id',
            array('interfaceName' => 'name', 'mac', 'ip')
        );
        $this->addJoin(
            'interface_types',
            'interface_types.id = interfaces.type_id',
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

        $data                   = array();
        $data['name']           = $object->getName();
        $data['snmp_version']   = $object->getSnmpVersion();
        $data['snmp_community'] = $object->getSnmpCommunity();
        $data['location_id']    = $object->getLocation()->getId();
        $data['type_id']        = $object->getType()->getId();

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
        $data                   = array();
        $data['name']           = $object->getName();
        $data['snmp_version']   = $object->getSnmpVersion();
        $data['snmp_community'] = $object->getSnmpCommunity();
        $data['location_id']    = $object->getLocation()->getId();
        $data['type_id']        = $object->getType()->getId();

        return $this->executeUpdateById($data, $object);
    }
}
