<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Model\Reports;

/**
 * Class InterfacesTraffic
 *
 * @package Reports\Model\Reports
 */
class InterfacesTraffic extends AbstractReport
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        if (isset($data['interface_bandwidth'])) {
            $this->data = $data['interface_bandwidth'];
        }

        return $this;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getReport()
    {
        if (!isset($this->data['device']['id'])) {
            throw new \InvalidArgumentException('The device ID was not found in the provided data');
        }

        $noDays = 1;

        if (isset($this->data['days'])) {
            $noDays = $this->data['days'];
        }

        $days             = array();
        $interfaces       = array();
        $trafficData      = array();
        $oneDaySeconds    = 86400;
        $xDaysSeconds     = $noDays * $oneDaySeconds;
        $currentTime      = time();
        $timeFromMidnight = strtotime(date('Y-m-d', $currentTime) . ' 00:00:00');
        $lastXDays        = $currentTime - ($currentTime - $timeFromMidnight) - $xDaysSeconds;

        $timeTo = $this->model->getWhere('time', $timeFromMidnight, 'bandwidth_logs', '<');
        $this->model->addWhere($timeTo, true);

        $result = $this->model->getLastSeconds($lastXDays, $this->data['device']['id'], 0);

        // Getting the minimum octets IN/OUT
        foreach ($result as $data) {
            $date          = $data['date'];
            $interfaceName = $data['interface_name'];

            if (!isset($trafficData[$date][$interfaceName])) {
                $trafficData[$date][$interfaceName] = array(
                    'octets_in' => $data['octets_in'],
                    'octets_out' => $data['octets_out'],
                    'id' => $data['id'],
                );
            } else {
                if ($data['octets_in'] < $trafficData[$date][$interfaceName]['octets_in']) {
                    $trafficData[$date][$interfaceName]['octets_in'] = $data['octets_in'];
                }

                if ($data['octets_out'] < $trafficData[$date][$interfaceName]['octets_out']) {
                    $trafficData[$date][$interfaceName]['octets_out'] = $data['octets_out'];
                }
            }
        }

        // Calculating the traffic data
        foreach ($result as $data) {
            $date                       = $data['date'];
            $interfaceName              = $data['interface_name'];
            $interfaces[$interfaceName] = $data['oid_index'];

            if (!isset($days[$date][$interfaceName])) {
                $days[$date][$interfaceName] = array(
                    'octets_in' => 0,
                    'octets_out' => 0,
                );

                if (isset($trafficData[$date][$interfaceName]['octets_in'])) {
                    $days[$date][$interfaceName]['octets_id'] -= $trafficData[$date][$interfaceName]['octets_in'];
                }

                if (isset($trafficData[$date][$interfaceName]['octets_out'])) {
                    $days[$date][$interfaceName]['octets_out'] -= $trafficData[$date][$interfaceName]['octets_out'];
                }
            }

            if($data['id'] != $trafficData[$date][$interfaceName]['id']) {
                if($days[$date][$interfaceName]['octets_in'] < $data['octets_in']) {
                    $days[$date][$interfaceName]['octets_in'] = $data['octets_in'];
                }

                if($days[$date][$interfaceName]['octets_out'] < $data['octets_out']) {
                    $days[$date][$interfaceName]['octets_out'] = $data['octets_out'];
                }
            }
        }

        return array(
            'days' => $days,
            'interfaces' => $interfaces,
        );
    }
}
