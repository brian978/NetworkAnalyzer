<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;

class AbstractDbHelperModel extends AbstractTableGateway
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
     * Used to generate a where condition
     *
     * @param string     $field
     * @param string|int $value
     * @param string     $table
     *
     * @return string
     */
    protected function getWhere($field, $value, $table = null)
    {
        if ($table === null) {
            $table = $this->table;
        }

        $identifierChain = array(
            $table,
            $field
        );

        return $this->platform->quoteIdentifierChain(
            $identifierChain
        ) . '=' . $this->platform->quoteValue($value);
    }

    /**
     * Used to add a where condition
     *
     * @param string     $field
     * @param string|int $value
     * @param string     $table
     *
     * @return $this
     */
    public function addWhere($field, $value, $table = null)
    {
        // Resetting the where if the fetch method has already run
        if ($this->fetchRun === true) {
            $this->resetSelectJoinWhere();
        }

        $this->where[] = $this->getWhere($field, $value, $table);

        return $this;
    }

    /**
     * Used to add a where condition
     *
     * @param        $name
     * @param        $condition
     * @param string $columns
     * @param string $type
     *
     * @return $this
     */
    public function addJoin(
        $name,
        $condition,
        $columns = Select::SQL_STAR,
        $type = Select::JOIN_INNER
    ) {
        // Resetting the where if the fetch method has already run
        if ($this->fetchRun === true) {
            $this->resetSelectJoinWhere();
        }

        $this->join[] = array(
            $name,
            $condition,
            $columns,
            $type
        );

        return $this;
    }

    /**
     * Used to add a column for the select
     *
     * @param array $columns
     *
     * @return $this
     */
    public function addColumns(array $columns)
    {
        $this->columns = array_merge($this->columns, $columns);

        return $this;
    }
}