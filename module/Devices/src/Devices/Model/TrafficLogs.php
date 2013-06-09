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
use Zend\Db\Sql\Select;

class TrafficLogs extends AbstractModel implements LogsInterface
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
     * @param \Poller\Object\Traffic\Connection $object
     * @return int
     */
    protected function doInsert($object)
    {
        $result = 0;

        $data               = array();
        $data['time']       = time();
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
}
