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
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\TableGateway;

class DevicesModel extends TableGateway
{
    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        parent::__construct('devices', $adapter);
    }

    /**
     * @param Device $device
     * @return int
     */
    public function save(Device $device)
    {
        if ($device->getId() === 0)
        {
            return $this->doInsert($device);
        }
        else
        {
            return $this->doUpdate($device);
        }
    }

    /**
     * This returns the number of affected rows
     *
     * @param Device $device
     * @return int
     */
    protected function doInsert(Device $device)
    {
        $result = 0;

        $data                = array();
        $data['name']        = $device->getName();
        $data['location_id'] = $device->getLocation()->getId();
        $data['dtype_id']    = $device->getType()->getId();

//        try
//        {
            // If successful will return the number of rows
            $result = $this->insert($data);
//        }
//        catch(\Exception $e)
//        {
//
//        }

        return $result;
    }

    /**
     * This returns the number of affected rows
     *
     * @param Device $device
     * @return int
     */
    protected function doUpdate(Device $device)
    {
    }
}