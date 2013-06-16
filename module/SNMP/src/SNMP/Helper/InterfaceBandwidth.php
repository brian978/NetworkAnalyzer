<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Helper;

use Devices\Entity\Device as DeviceEntity;
use Devices\Entity\Log;
use Devices\Model\BandwidthLogs;
use SNMP\Manager\Objects\Device\Device;
use SNMP\Manager\Objects\Iface\Iface;
use Zend\Db\Adapter\Adapter;

class InterfaceBandwidth implements HelperInterface
{
    /**
     * @var BandwidthCalculator
     */
    protected $calculator;

    /**
     * @var BandwidthLogs
     */
    protected $logsModel;

    /**
     * @var int
     */
    protected $logTime;

    /**
     * @var bool
     */
    public $doLogging = false;

    /**
     * @param BandwidthCalculator          $calculator
     * @param \Devices\Model\BandwidthLogs $logsModel
     */
    public function __construct(BandwidthCalculator $calculator, BandwidthLogs $logsModel)
    {
        $this->calculator = $calculator;
        $this->logsModel  = $logsModel;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        $args         = func_get_args();
        $deviceObject = $args[0];
        $device       = $args[1];

        if ($deviceObject instanceof DeviceEntity == false) {
            $message = 'The device object must be of type %s, %s given';
            $message = sprintf($message, '\Devices\Entity\Device', get_class($deviceObject));
            throw new \InvalidArgumentException($message);
        }

        foreach ($device->getInterfaces() as $interface) {
            $logData = $this->doLogging;

            /**
             * -------------------------------------
             * GETTING DATA AND CALCULATING
             * -------------------------------------
             */
            $data = $this->getLogData($interface);

            if (!empty($data)) {
                if (!empty($data['last']) && !empty($data['prev'])) {
                    $this->setInterfaceData($interface, $data['last'], $data['prev']);
                }
            }

            if ($logData === true) {
                $this->logData($this->createLogsObject($deviceObject, $interface));
            }
        }
    }

    /**
     * @param Iface $interface
     * @return array
     */
    protected function getLogData(Iface $interface)
    {
        if ($this->doLogging === false) {
            $this->logsModel->setLimit(2);
        } else {
            $this->logTime = time();
            $this->logsModel->setLimit(1);
        }

        /** @var $device Device */
        $device = $interface->getParentObject();

        $last     = array();
        $prev     = array();
        $deviceId = $device->getDeviceEntity()->getId();
        $data     = $this->logsModel->getLastEntries(
            $interface->getOidIndex(),
            $deviceId
        );

        // We need 2 logs to calculate properly
        if (!empty($data) && is_array($data)) {
            if (count($data) == 1) {
                $prev = array_shift($data);
                $last = array(
                    'uptime' => $device->getUptime(),
                    'octets_in' => $interface->getIn()->get(),
                    'octets_out' => $interface->getOut()->get(),
                    'time' => $this->logTime
                );
            } elseif (count($data) === 2) {
                $last = array_shift($data);
                $prev = array_shift($data);
            }
        }

        return array(
            'last' => $last,
            'prev' => $prev,
        );
    }

    /**
     * @param Iface $interface
     * @param       $last
     * @param       $prev
     * @return $this
     */
    protected function setInterfaceData(Iface $interface, $last, $prev)
    {
        /**
         * ------------------
         * DIFFS
         * ------------------
         */
        $diffInOctets  = $last['octets_in'] - $prev['octets_in'];
        $diffOutOctets = $last['octets_out'] - $prev['octets_out'];
        $diffTime      = $last['time'] - $prev['time'];
        var_dump($diffInOctets);
        /**
         * ------------------
         * IN BANDWIDTH
         * ------------------
         */
        $inData = $this->calculator->__invoke($diffInOctets, $diffTime);
        $interface->setBandwidthIn($inData['bandwidth']);
        $interface->setBandwidthInType($inData['type']);

        /**
         * ------------------
         * OUT BANDWIDTH
         * ------------------
         */
        $outData = $this->calculator->__invoke($diffOutOctets, $diffTime);
        $interface->setBandwidthOut($outData['bandwidth']);
        $interface->setBandwidthOutType($outData['type']);

        return $this;
    }

    /**
     * @param DeviceEntity $deviceObject
     * @param Iface        $interface
     * @return Log
     */
    protected function createLogsObject(DeviceEntity $deviceObject, Iface $interface)
    {
        $device = $interface->getParentObject();

        $bandwidthIn  = $interface->getBandwidthIn() * (pow(1000, $interface->getBandwidthInType()));
        $bandwidthOut = $interface->getBandwidthOut() * (pow(1000, $interface->getBandwidthOutType()));

        $logObject = new Log();
        $logObject->setUptime($device->getUptime());
        $logObject->setOidIndex($interface->getOidIndex());
        $logObject->setDevice($deviceObject);
        $logObject->setInterfaceName($interface->getName()->get());
        $logObject->setMac($interface->getMac()->get());
        $logObject->setOctetsIn($interface->getIn()->get());
        $logObject->setOctetsOut($interface->getOut()->get());
        $logObject->setBandwidthIn($bandwidthIn);
        $logObject->setBandwidthOut($bandwidthOut);
        $logObject->setIp($interface->getIp()->get());
        $logObject->setNetmask($interface->getNetmask()->get());
        $logObject->setTime($this->logTime);
        $logObject->setDiscontinuityCounter($interface->getDiscontinuityCounter()->get());

        return $logObject;
    }

    /**
     * @param Log $logObject
     * @return $this
     */
    protected function logData(Log $logObject)
    {
        // Saving the data
        $this->logsModel->save($logObject);

        return $this;
    }
}
