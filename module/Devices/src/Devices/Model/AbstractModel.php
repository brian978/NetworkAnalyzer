<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

use Devices\Entity\AbstractEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\TableGateway\AbstractTableGateway;

abstract class AbstractModel extends AbstractTableGateway
{
    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        $this->initialize();
    }

    abstract protected function doInsert($object);

    abstract protected function doUpdate($object);

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
     * Retrieves all the data from the database
     *
     * @return array
     */
    public function fetch()
    {
        $rows = array();

        $select = $this->getSql()->select();;

        try
        {
            $resultSet = $this->selectWith($select);
        }
        catch (\Exception $e)
        {
        }

        if(isset($resultSet))
        {
            if($resultSet->count() > 0)
            {
                foreach($resultSet as $row)
                {
                    $rows[$row['id']] = $row->getArrayCopy();
                }
            }
        }

        return $rows;
    }
}