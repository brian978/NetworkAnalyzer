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

class InterfacesModel extends AbstractModel
{
    protected $table = 'interfaces';

    public function fetch()
    {
        $this->addJoin(
            'devices',
            'devices.id = interfaces.device_id',
            array('deviceName' => 'name')
        );
        $this->addJoin(
            'interface_types',
            'interface_types.id = interfaces.type_id',
            array('typeName' => 'name')
        );

        return parent::fetch();
    }

    /**
     * This returns the number of affected rows
     *
     * @param \Devices\Entity\Iface $object
     *
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data              = array();
        $data['name']      = $object->getName();
        $data['mac']       = $object->getMac();
        $data['ip']        = $object->getIp();
        $data['type_id']   = $object->getType()->getId();
        $data['device_id'] = $object->getDevice()->getId();

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
     * @param \Devices\Entity\Iface $object
     *
     * @return int
     */
    protected function doUpdate($object)
    {
        $data              = array();
        $data['name']      = $object->getName();
        $data['mac']       = $object->getMac();
        $data['ip']        = $object->getIp();
        $data['type_id']   = $object->getType()->getId();
        $data['device_id'] = $object->getDevice()->getId();

        return $this->executeUpdateById($data, $object);
    }
}
