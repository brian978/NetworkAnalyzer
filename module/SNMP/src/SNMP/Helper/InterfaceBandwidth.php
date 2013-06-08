<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Helper;

use Devices\Entity\Device;
use Devices\Entity\Log;
use Devices\Model\LogsModel;
use SNMP\Manager\Objects\Iface\Iface;
use Zend\Db\Adapter\Adapter;

class InterfaceBandwidth implements HelperInterface
{
    /**
     * @var BandwidthCalculator
     */
    protected $calculator;

    /**
     * @var LogsModel
     */
    protected $logsModel;

    /**
     * @var int
     */
    protected $logTime;

    /**
     * @param BandwidthCalculator      $calculator
     * @param \Devices\Model\LogsModel $logsModel
     */
    public function __construct(BandwidthCalculator $calculator, LogsModel $logsModel)
    {
        $this->calculator = $calculator;
        $this->logsModel  = $logsModel;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)
            && is_object($this->$name)
            && $this->$name instanceof HelperInterface
        ) {
            return call_user_func_array(array($this->$name, '__invoke'), $arguments);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        $args         = func_get_args();
        $deviceObject = $args[0];
        $device       = $args[1];

        if ($deviceObject instanceof Device == false) {
            $message = 'The device object must be of type %s, %s given';
            $message = sprintf($message, '\Devices\Entity\Device', get_class($deviceObject));
            throw new \InvalidArgumentException($message);
        }

        $this->logTime = time();

        $deviceId = $deviceObject->getId();

        foreach ($device->getInterfaces() as $interface) {

            $logData = true;

            /**
             * -------------------------------------
             * GETTING DATA AND CALCULATING
             * -------------------------------------
             */
            $this->logsModel->limit = 1;

            $interfaceData = $this->logsModel->getLastEntries(
                $interface->getOidIndex(),
                $deviceId
            );

            // We need 2 logs to calculate properly
            if (!empty($interfaceData)) {

                $last = array();
                $prev = array();

                if (count($interfaceData) == 1) {
                    $last = array(
                        'uptime' => $device->getUptime(),
                        'octets_in' => $interface->getIn()->get(),
                        'octets_out' => $interface->getOut()->get(),
                        'time' => $this->logTime
                    );

                    $prev = array_shift($interfaceData);
                }

                if (!empty($last) && !empty($prev)) {
                    $logData = $this->setInterfaceData($interface, $last, $prev);
                }
            }

            $this->logData(
                $this->createLogsObject($deviceObject, $interface),
                $logData // Just a flag that tells if to log or not
            );
        }
    }

    /**
     * @param Iface $interface
     * @param       $last
     * @param       $prev
     * @return bool
     */
    protected function setInterfaceData(Iface $interface, $last, $prev)
    {
        $logData = true;

        // Queries with the same timestamp must not be saved
        if (strpos($last['uptime'], $prev['uptime']) === 0) {
            $logData = false;
        }

        // Calculating the differences
        $diffInOctets  = $last['octets_in'] - $prev['octets_in'];
        $diffOutOctets = $last['octets_out'] - $prev['octets_out'];
        $diffTime      = $last['time'] - $prev['time'];

        /**
         * ------------------
         * IN BANDWIDTH
         * ------------------
         */
        $inData = $this->calculator($diffInOctets, $diffTime);
        $interface->setBandwidthIn($inData['bandwidth']);
        $interface->setBandwidthInType($inData['type']);

        /**
         * ------------------
         * OUT BANDWIDTH
         * ------------------
         */
        $outData = $this->calculator($diffOutOctets, $diffTime);
        $interface->setBandwidthOut($outData['bandwidth']);
        $interface->setBandwidthOutType($outData['type']);

        return $logData;
    }

    /**
     * @param Device $deviceObject
     * @param Iface  $interface
     * @return Log
     */
    protected function createLogsObject(Device $deviceObject, Iface $interface)
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
     * @param Log  $logObject
     * @param bool $save
     * @return $this
     */
    protected function logData(Log $logObject, $save = true)
    {
        // Saving the data
        if ($save === true) {
            $this->logsModel->save($logObject);
        }

        return $this;
    }
}