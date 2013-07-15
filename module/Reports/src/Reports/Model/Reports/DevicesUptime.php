<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Model\Reports;

class DevicesUptime extends AbstractReport
{
    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        if (isset($data['devices_uptime'])) {
            $this->data = $data['devices_uptime'];
        }

        return $this;
    }

    public function getReport()
    {
        $results = array(
            'devices' => array(),
            'uptimes' => array(),
        );

        $noDays = 1;

        if (isset($this->data['days'])) {
            $noDays = $this->data['days'];
        }

        $oneDaySeconds    = 86400;
        $xDaysSeconds     = $noDays * $oneDaySeconds;
        $currentTime      = time();
        $timeFromMidnight = strtotime(date('Y-m-d', $currentTime) . ' 00:00:00');
        $lastXDays        = $currentTime - ($currentTime - $timeFromMidnight) - $xDaysSeconds;

        $timeFrom = $this->model->getWhere('time', $lastXDays, $this->model->getTable(), '>');
//        $timeTo   = $this->model->getWhere('time', $timeFromMidnight, $this->model->getTable(), '<');
        $timeTo = $this->model->getWhere('time', time(), $this->model->getTable(), '<');

        $select = $this->model->getSelect();
        $select->columns(array('id', 'uptime', 'date', 'time'));
        $select->order($this->model->getTable() . '.time DESC');
        $select->group($this->model->getTable() . '.date');
        $select->limit(7);

        foreach ($this->data['devices'] as $deviceId) {
            $this->model->addWhere($timeFrom, true);
            $this->model->addWhere($timeTo, true);
            $this->model->addWhere('device_id', $deviceId);
            $this->model->addJoin('devices', 'devices.id = ' . $this->model->getTable() . '.device_id', array('name'));

            $result = $this->model->fetch();

            if (is_array($result)) {
                foreach ($result as $data) {
                    if (!isset($results['uptimes'][$data['date']])) {
                        $results['uptimes'][$data['date']] = array();
                    }

                    $results['uptimes'][$data['date']][$deviceId] = $data;
                    $results['devices'][$deviceId]                = $data['name'];
                }
            }
        }

        return $results;
    }
}
