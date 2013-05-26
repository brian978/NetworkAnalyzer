<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Model;

use Library\Entity\AbstractEntity;

abstract class AbstractDbModel extends AbstractDbHelperModel
{
    abstract protected function doInsert($object);

    abstract protected function doUpdate($object);

    abstract public function doDelete($object);

    /**
     * @param $id
     *
     * @return array
     */
    public function getInfo($id)
    {
        $this->addWhere('id', $id);

        return current($this->fetch());
    }

    /**
     *
     * @param AbstractEntity $object
     *
     * @return int
     */
    public function save(AbstractEntity $object)
    {
        if ($object->getId() === 0) {
            $result = $this->doInsert($object);
        } else {
            $result = $this->doUpdate($object);
        }

        return $result;
    }

    /**
     * This method is used to apply a custom row processing by the child models
     *
     * @param $row
     * @return object
     */
    protected function processRow(\ArrayObject $row)
    {
        return $row;
    }

    /**
     * @param array          $data
     * @param AbstractEntity $object
     *
     * @return int
     */
    protected function executeUpdateById(array $data, AbstractEntity $object)
    {
        $result = 0;

        try {
            // If successful will return the number of rows
            $result = $this->update(
                $data,
                array($this->getWhere('id', $object->getId()))
            );
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * Retrieves all the data from the database
     *
     * @return array
     */
    public function fetch()
    {
        $rows   = array();
        $select = $this->getSql()->select()->where($this->where);

        // Adding the joins for the select
        foreach ($this->join as $join) {
            call_user_func_array(array($select, 'join'), $join);
        }

        try {
            $resultSet = $this->selectWith($select);
        } catch (\Exception $e) {
        }

        if (isset($resultSet)) {
            if ($resultSet->count() > 0) {
                foreach ($resultSet as $row) {
                    $rows[$row['id']] = $this->processRow($row);
                }
            }

            $this->fetchRun = true;
        }

        return $rows;
    }
}
