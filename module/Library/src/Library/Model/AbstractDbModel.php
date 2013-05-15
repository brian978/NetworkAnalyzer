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
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;

abstract class AbstractDbModel extends AbstractTableGateway
{
    /**
     * Connection object
     *
     * @var \Zend\Db\Adapter\Driver\ConnectionInterface
     */
    protected $connection;

    /**
     * Adapter platform
     *
     * @var \Zend\Db\Adapter\Platform\PlatformInterface
     */
    protected $platform;

    /**
     * An array of where conditions used by the fetch method
     *
     * @var array
     */
    protected $where = array();

    /**
     * An array joins used by the fetch method
     *
     * @var array
     */
    protected $join = array();

    /**
     * This is used to determine when to reset the where condition
     *
     * @var boolean
     */
    protected $fetchRun = false;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter    = $adapter;
        $this->platform   = $this->adapter->getPlatform();
        $this->connection = $this->adapter->getDriver()->getConnection();

        $this->initialize();
    }

    abstract protected function doInsert($object);

    abstract protected function doUpdate($object);

    /**
     * @param $id
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
     * @return int
     */
    public function save(AbstractEntity $object)
    {
        if ($object->getId() === 0)
        {
            $result = $this->doInsert($object);
        }
        else
        {
            $result = $this->doUpdate($object);
        }

        return $result;
    }

    /**
     * Used to generate a where condition
     *
     * @param string     $field
     * @param string|int $value
     * @param string     $table
     * @return string
     */
    protected function getWhere($field, $value, $table = null)
    {
        if ($table === null)
        {
            $table = $this->table;
        }

        $identifierChain = array(
            $table,
            $field
        );

        return $this->platform->quoteIdentifierChain($identifierChain) . '=' . $this->platform->quoteValue($value);
    }

    /**
     * @return $this
     */
    protected function resetSelectJoinWhere()
    {
        $this->where    = array();
        $this->join     = array();
        $this->fetchRun = false;

        return $this;
    }

    /**
     * Used to add a where condition
     *
     * @param string     $field
     * @param string|int $value
     * @param string     $table
     * @return $this
     */
    public function addWhere($field, $value, $table = null)
    {
        // Resetting the where if the fetch method has already run
        if ($this->fetchRun === true)
        {
            $this->resetSelectJoinWhere();
        }

        $this->where[] = $this->getWhere($field, $value, $table);

        return $this;
    }

    /**
     * Used to add a where condition
     *
     * @param        $name
     * @param        $on
     * @param string $columns
     * @param string $type
     * @return $this
     */
    public function addJoin($name, $on, $columns = Select::SQL_STAR, $type = Select::JOIN_INNER)
    {
        // Resetting the where if the fetch method has already run
        if ($this->fetchRun === true)
        {
            $this->resetSelectJoinWhere();
        }

        $this->join[] = array(
            $name,
            $on,
            $columns,
            $type
        );

        return $this;
    }


    /**
     * Used to add a column for the select
     *
     * @param array $columns
     * @return $this
     */
    public function addColumns(array $columns)
    {
        $this->columns = array_merge($this->columns, $columns);

        return $this;
    }

    /**
     * This method is used to apply a custom row processing by the child models
     *
     * @param $row
     * @return mixed
     */
    protected function processRow($row)
    {
        return $row;
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
        foreach ($this->join as $join)
        {
            call_user_func_array(array($select, 'join'), $join);
        }

        try
        {
            $resultSet = $this->selectWith($select);
        }
        catch (\Exception $e)
        {
        }

        if (isset($resultSet))
        {
            if ($resultSet->count() > 0)
            {
                foreach ($resultSet as $row)
                {
                    $rows[$row['id']] = $this->processRow($row);
                }
            }

            $this->fetchRun = true;
        }

        return $rows;
    }
}