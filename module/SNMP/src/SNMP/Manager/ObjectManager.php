<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager;

use SNMP\Manager\Objects\Device\Device;
use SNMP\Manager\Objects\Iface\Iface;

/**
 * Class ObjectManager
 *
 * @package SNMP\Manager
 *
 * @property string uptime
 * @property string contact
 * @property string name
 * @property string location
 * @property string ip
 * @property string interface_name
 * @property string interface_mac
 * @property string interface_in
 * @property string interface_out
 * @property string interface_admin_status
 * @property string interface_status
 * @property string interface_queue_length
 */
class ObjectManager
{
    protected $objectsNamespace = 'Objects';

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var \SNMP\Manager\Objects\Device
     */
    protected $device;

    /**
     * Contains the available objects the manager can handle
     *
     * @var array
     */
    protected $objects = array(
        'device' => array(
            'uptime' => '.1.3.6.1.2.1.1.3',
            'contact' => '.1.3.6.1.2.1.1.4',
            'name' => '.1.3.6.1.2.1.1.5',
            'location' => '.1.3.6.1.2.1.1.6',
        ),
        'iface' => array(
            'ip' => '.1.3.6.1.2.1.4.20',
            'name' => '.1.3.6.1.2.1.2.2.1.2',
            'mac' => '.1.3.6.1.2.1.2.2.1.6',
            // Octets
            'in' => '.1.3.6.1.2.1.2.2.1.10',
            // Octets
            'out' => '.1.3.6.1.2.1.2.2.1.16',
            // 1: up, 2: down, 3: testing
            'admin_status' => '.1.3.6.1.2.1.2.2.1.7',
            // 1: up, 2: down, 3: testing, 4: unknown, 5: dormant, 6: notPresent, 7: lowerLayerDown
            'status' => '.1.3.6.1.2.1.2.2.1.8',
            'queue_length' => '.1.3.6.1.2.1.2.2.1.21'
        ),
    );

    /**
     * @param SessionManager $sessionManager
     */
    public function __construct(SessionManager $sessionManager)
    {
        $this->device = new Device($this);

        $this->setSessionManager($sessionManager);
        $this->collectData();
    }

    /**
     * @param SessionManager $sessionManager
     */
    public function setSessionManager(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * Collects all the available data for the current device
     *
     * @return $this
     */
    protected function collectData()
    {
        $startTime = microtime(true);

        // Creating all the interfaces with minimal data so we can properly attach
        // the interface information to the proper interface instance
        $interfaces           = array();
        $snmpInterfaceIndexes = $this->sessionManager->walk('1.3.6.1.2.1.2.2.1.1');

        foreach ($snmpInterfaceIndexes as $oid) {
            $oidIndex              = Iface::extractOidIndex($oid);
            $interface             = new Iface($this->device);
            $interfaces[$oidIndex] = $interface;

            $interface->setOidIndex($oidIndex);
        }

        if (count($interfaces) > 0) {
            foreach ($this->objects as $type => $objects) {
                foreach ($objects as $alias => $oid) {

                    // Creating the class name using the type of
                    // object (device or iface) and alias
                    // of the OID object
                    $className = $this->createClassName($type, $alias);

                    if (class_exists($className)) {
                        echo $className . PHP_EOL;
                        $snmpInfo    = $this->sessionManager->walk($oid);
                        $classObject = new $className($this->device);
                        $classObject->process($snmpInfo);
                    }
                }
            }
        }

        echo microtime(true) - $startTime;

//        var_dump($this->device);
//        var_dump($this->device->getInterfaceByOidIndex(1)->getMac());
        var_dump($this->sessionManager->walk('.1.3.6.1.2.1.2.2.1.6'));
        return $this;
    }

    /**
     * Wrapper for the getObjectOID (for now)
     *
     * @param $name
     * @throws \RuntimeException
     * @return string
     */
    public function __get($name)
    {
        if (!is_object($this->sessionManager)) {
            throw new \RuntimeException('The session manager has not been set');
        }

        $snmpInfo = $this->sessionManager->walk($this->getOID($name));

        return $snmpInfo;
    }

    /**
     * Returns all the supported objects
     *
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Returns the OID of the requested object
     *
     * @param string $objectName
     * @return string
     */
    public function getOID($objectName)
    {
        $result = '';

        if (strpos($objectName, '_') !== 0) {
            $pieces = explode('_', $objectName);

            if (isset($this->objects[$pieces[0]][$pieces[1]])) {
                $result = $this->objects[$pieces[0]][$pieces[1]];
            }
        }

        return $result;
    }

    /**
     * Returns the ALIAS of the requested OID
     *
     * @param string $oidValue
     * @return string
     */
    public function getAlias($oidValue)
    {
        $result = '';

        foreach ($this->objects as $values) {
            if (is_array($values)) {
                foreach ($values as $alias => $oid) {
                    if ($oid == $oidValue) {
                        $result = $alias;
                        break 2;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Creates a class name using a string
     *
     * @param $type
     * @param $alias
     * @return string
     */
    protected function createClassName($type, $alias)
    {
        $type  = ucfirst($type);
        $alias = ucfirst($alias);

        return __NAMESPACE__ . '\\' . $this->objectsNamespace . '\\' . $type . '\\' . $alias;
    }
}
