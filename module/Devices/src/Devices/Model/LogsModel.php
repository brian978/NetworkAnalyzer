<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;

class LogsModel extends AbstractModel
{
    protected $table = 'logs';

    /**
     * @param $oidIndex
     * @param $deviceId
     * @return array
     */
    public function getLastEntry($oidIndex, $deviceId)
    {
        $where = array(
            $this->getWhere('oid_index', $oidIndex),
            $this->getWhere('device_id', $deviceId)
        );

        // Building the SELECT for the INNER JOIN
        $select = new Select('logs');
        $select->columns(array('oid_index', 'maxtime' => new Expression('MAX(time)')));
        $select->where($where);

        // Adding the rest of the conditions
        $this->addJoin(array('ss' => $select), 'logs.oid_index = ss.oid_index AND logs.time = ss.maxtime');
        $this->addWhere($where, true);

        return parent::fetch();
    }

    /**
     * @param $oidIndex
     * @param $deviceId
     * @return array
     */
    public function getLastEntries($oidIndex, $deviceId)
    {
        // Adding the rest of the conditions
        $this->addWhere('oid_index', $oidIndex);
        $this->addWhere('device_id', $deviceId);

        return parent::fetch();
    }

    // find better way
    protected function proxySelect(Select $select)
    {
        $select->order('time DESC');
        $select->limit(2);
    }

    /**
     * @param \Devices\Entity\Log $object
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data                   = array();
        $data['uptime']         = $object->getUptime();
        $data['time']           = $object->getTime();
        $data['oid_index']      = $object->getOidIndex();
        $data['interface_name'] = $object->getInterfaceName();
        $data['octets_in']      = $object->getOctetsIn();
        $data['octets_out']     = $object->getOctetsOut();
        $data['mac']            = $object->getMac();
        $data['device_id']      = $object->getDevice()->getId();

        try {
            // If successful will return the number of rows
            $result = $this->insert($data);
        } catch (\Exception $e) {
        }

        return $result;
    }
}