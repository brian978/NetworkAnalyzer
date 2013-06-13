<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Reports\Model\Reports;

use Library\Model\AbstractDbModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class InterfaceBandwidth
 *
 * @package Reports\Model\Reports
 */
class InterfaceBandwidth implements ReportInterface, ServiceLocatorAwareInterface
{
    /**
     * @var \Devices\Model\BandwidthLogs
     */
    protected $model;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $data = array();

    public function getReport()
    {
        ini_set('memory_limit', '256M');

        if (!isset($this->data['device']['id'])) {
            throw new \InvalidArgumentException('The device ID was not found in the provided data');
        }
        $startTime        = microtime(true);
        $xDaysSeconds     = 432000; // For now five days
        $currentTime      = time();
        $timeFromMidnight = strtotime(date('Y-m-d', $currentTime) . ' 00:00:00');
        $lastXDays        = $currentTime - ($currentTime - $timeFromMidnight) - $xDaysSeconds;

        $result = $this->model->getLastSeconds($lastXDays, $this->data['device']['id'], 0);

        $secondsIn24 = 24 * 60 * 60;
        $days        = array();

        foreach ($result as $data) {
            $date          = $data['date'];
            $interfaceName = $data['interface_name'];

            if (isset($days[$date][$interfaceName])) {
                $days[$date][$interfaceName]['octets_in'] += $data['octets_in'];
                $days[$date][$interfaceName]['octets_out'] += $data['octets_out'];
            } else {
                $days[$date][$interfaceName] = array(
                    'octets_in' => 0,
                    'octets_out' => 0,
                );
            }
        }
        echo microtime(true) - $startTime;
        echo '<pre>' . print_r($days, 1) . '</pre>';

        return $days;
    }

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
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param AbstractDbModel $model
     */
    public function setModel(AbstractDbModel $model)
    {
        $this->model = $model;
    }
}
