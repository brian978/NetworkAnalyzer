<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

use SNMP\Model\Logs\LogsInterface;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Select;

class BandwidthLogs extends AbstractModel implements LogsInterface
{
    /**
     * @var string
     */
    protected $table = 'bandwidth_logs';

    /**
     * The number of results to return
     *
     * @var int
     */
    protected $limit = 0;

    /**
     * @param $limit
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLastSeconds($seconds, $deviceId)
    {
        $where = $this->getWhere('time', time() - $seconds, null, '>');

        $this->addWhere($where, true);
        $this->addWhere('device_id', $deviceId);

        return parent::fetch();
    }

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
        $select = new Select($this->table);
        $select->columns(array('oid_index', 'maxtime' => new Expression('MAX(time)')));
        $select->where($where);

        // Adding the rest of the conditions
        $this->addJoin(
            array('ss' => $select),
            $this->table . '.oid_index = ss.oid_index AND ' . $this->table . '.time = ss.maxtime'
        );
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

        if (is_numeric($this->limit) && $this->limit > 0) {
            $select->limit($this->limit);

            // Resetting the limit
            $this->limit = 0;
        }
    }

    /**
     * @param \Devices\Entity\Log $object
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data                          = array();
        $data['uptime']                = $object->getUptime();
        $data['time']                  = $object->getTime();
        $data['oid_index']             = $object->getOidIndex();
        $data['interface_name']        = $object->getInterfaceName();
        $data['ip']                    = $object->getIp();
        $data['netmask']               = $object->getNetmask();
        $data['octets_in']             = $object->getOctetsIn();
        $data['octets_out']            = $object->getOctetsOut();
        $data['bandwidth_in']          = $object->getBandwidthIn();
        $data['bandwidth_out']         = $object->getBandwidthOut();
        $data['mac']                   = $object->getMac();
        $data['discontinuity_counter'] = $object->getDiscontinuityCounter();
        $data['device_id']             = $object->getDevice()->getId();

        try {
            // If successful will return the number of rows
            $result = $this->insert($data);
        } catch (\Exception $e) {
        }

        return $result;
    }
}
