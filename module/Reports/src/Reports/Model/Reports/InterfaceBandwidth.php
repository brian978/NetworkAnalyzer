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
        // TODO: Implement getReport() method.
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
        var_dump($this->data);

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
