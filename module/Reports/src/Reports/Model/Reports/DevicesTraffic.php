<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Model\Reports;

class DevicesTraffic extends AbstractReport
{
    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        if (isset($data['devices_traffic'])) {
            $this->data = $data['devices_traffic'];
        }

        return $this;
    }

    public function getReport()
    {
        if (!isset($this->data['device']['id'])) {
            throw new \InvalidArgumentException('The device ID was not found in the provided data');
        }

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
        $select->group('time');

        $this->model->addWhere('device_id', $this->data['device']['id']);
        $this->model->addWhere('type', strtoupper($this->data['traffic_type']));
        $this->model->addWhere($timeFrom, true);
        $this->model->addWhere($timeTo, true);

        $result = $this->model->fetch();

        return $result;
    }
}
