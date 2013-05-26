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

class AbstractModel extends AbstractDbModel
{
    /**
     * This returns the number of affected rows
     *
     * @param \Devices\Entity\Location $object
     *
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data         = array();
        $data['name'] = $object->getName();

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
     * @param \Devices\Entity\Location $object
     *
     * @return int
     */
    protected function doUpdate($object)
    {
        $data         = array();
        $data['name'] = $object->getName();

        return $this->executeUpdateById($data, $object);
    }

    /**
     * @param \ArrayObject $object
     * @return int
     */
    public function doDelete($object)
    {
        $result = 0;

        try {
            $result = $this->delete($this->getWhere('id', $object->id));
        } catch (\Exception $e) {
        }

        return $result;
    }
}
