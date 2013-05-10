<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

class DevicesModel extends AbstractModel
{
    protected $table = 'devices';

    /**
     * This returns the number of affected rows
     *
     * @param \Devices\Entity\Device $object
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data                = array();
        $data['name']        = $object->getName();
        $data['location_id'] = $object->getLocation()->getId();
        $data['dtype_id']    = $object->getType()->getId();

        try
        {
            // If successful will return the number of rows
            $result = $this->insert($data);
        }
        catch(\Exception $e)
        {

        }

        return $result;
    }

    /**
     * This returns the number of affected rows
     *
     * @param \Devices\Entity\Device $object
     * @return int
     */
    protected function doUpdate($object)
    {

    }
}