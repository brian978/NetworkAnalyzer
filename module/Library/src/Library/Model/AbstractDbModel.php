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
use Library\Entity\EntityInterface;
use Zend\Db\Sql\Select;

abstract class AbstractDbModel extends AbstractDbHelperModel
{
    abstract protected function doInsert($object);

    abstract protected function doUpdate($object);

    abstract public function doDelete($object);

    /**
     * @var \Zend\Db\Sql\Select
     */
    protected $select;

    protected function resetSelectJoinWhere()
    {
        $this->select = null;

        return parent::resetSelectJoinWhere();
    }

    /**
     * @param $entityId
     *
     * @return \ArrayObject
     */
    public function getInfo($entityId)
    {
        $this->addWhere('id', $entityId);

        $entity = current($this->fetch());

        if (empty($entity)) {
            $entity = array();
        }

        return $entity;
    }

    /**
     *
     * @param EntityInterface $object
     *
     * @return int
     */
    public function save(EntityInterface $object)
    {
        if ($object->getId() === 0) {
            $result = $this->doInsert($object);
        } else {
            $result = $this->doUpdate($object);
        }

        return $result;
    }

    /**
     * @return Select
     */
    public function getSelect()
    {
        if ($this->select instanceof Select === false) {
            $this->select = $this->getSql()->select();

            if (!empty($this->selectColumns)) {
                $this->select->columns($this->selectColumns);
            }
        }

        return $this->select;
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
        $select = $this->getSelect();

        $select->where($this->where);

        // Adding the joins for the select
        foreach ($this->join as $join) {
            call_user_func_array(array($select, 'join'), $join);
        }

        // Something we might need to do something directly on the select
        // object so we need to create a proxySelect method to handle this
        if (method_exists($this, 'proxySelect')) {
            $this->proxySelect($select);
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
