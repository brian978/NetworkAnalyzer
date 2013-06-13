<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Sniffer\Model;

use Library\Model\AbstractDbModel;
use SNMP\Model\Logs\LogsInterface;
use Zend\Db\Sql\Select;

class TrafficLogs extends AbstractDbModel implements LogsInterface
{
    /**
     * @var string
     */
    protected $table = 'traffic_logs';

    /**
     * The number of results to return
     *
     * @var int
     */
    protected $limit = 0;

    /**
     * @param $seconds
     * @param $deviceId
     * @return array
     */
    public function getLastSeconds($seconds, $deviceId)
    {
        $where = $this->getWhere('time', time() - $seconds, null, '>');

        $this->addWhere($where, true);
        $this->addWhere('device_id', $deviceId);

        return parent::fetch();
    }

    /**
     * @param $limit
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
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
     * @param \Sniffer\Object\Traffic\Connection $object
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;
        $time   = time();

        $data               = array();
        $data['date']       = date('Y-m-d', $time);
        $data['time']       = $time;
        $data['type']       = $object->type;
        $data['src_ip']     = $object->srcIp;
        $data['src_port']   = $object->srcPort;
        $data['dst_ip']     = $object->dstIp;
        $data['dst_port']   = $object->dstPort;
        $data['iface_name'] = $object->getInterface()->getName()->get();
        $data['device_id']  = $object->getDeviceEntity()->getId();

        try {
            // If successful will return the number of rows
            $result = $this->insert($data);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * @param $object
     * @return int
     */
    protected function doUpdate($object)
    {
        unset($object);

        return 1;
    }

    /**
     * @param $object
     * @return int
     */
    public function doDelete($object)
    {
        unset($object);

        return 1;
    }
}
