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
            'uptime' => 'iso.3.6.1.2.1.1.3',
            'contact' => 'iso.3.6.1.2.1.1.4',
            'name' => 'iso.3.6.1.2.1.1.5',
            'location' => 'iso.3.6.1.2.1.1.6',
        ),
        'iface' => array(
            'ip' => 'iso.3.6.1.2.1.4.20.1.1',
            'ip_index' => 'iso.3.6.1.2.1.4.20.1.2',
            'name' => 'iso.3.6.1.2.1.2.2.1.2',
            'mac' => 'iso.3.6.1.2.1.2.2.1.6',
            // Octets
            'in' => 'iso.3.6.1.2.1.2.2.1.10',
            // Octets
            'out' => 'iso.3.6.1.2.1.2.2.1.16',
            // 1: up, 2: down, 3: testing
            'admin_status' => 'iso.3.6.1.2.1.2.2.1.7',
            // 1: up, 2: down, 3: testing, 4: unknown, 5: dormant, 6: notPresent, 7: lowerLayerDown
            'status' => 'iso.3.6.1.2.1.2.2.1.8',
            'queue_length' => 'iso.3.6.1.2.1.2.2.1.21'
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
        // Creating all the interfaces with minimal data so we can properly attach
        // the interface information to the proper interface instance
        $interfaces           = array();
        $snmpInterfaceIndexes = $this->sessionManager->walk('iso.3.6.1.2.1.2.2.1.1');

        foreach ($snmpInterfaceIndexes as $oid) {
            $oidIndex              = Iface::extractOidIndex($oid);
            $interface             = new Iface($this->device);
            $interfaces[$oidIndex] = $interface;

            $interface->setOidIndex($oidIndex);

            $this->device->attachInterface($interface, $oidIndex);
        }

        if (count($interfaces) > 0) {
            foreach ($this->objects as $type => $objects) {
                foreach ($objects as $alias => $oid) {

                    // Creating the class name using the type of
                    // object (device or iface) and alias
                    // of the OID object
                    $className = $this->createClassName($type, $alias);

                    if (class_exists($className)) {
                        $snmpInfo = $this->sessionManager->walk($oid);

                        foreach ($snmpInfo as $oid => $value) {
                            $classObject = new $className($this->device);
                            $classObject->process(array($oid => $value));
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     *
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
     * @param string $oid
     * @return mixed
     * @throws \RuntimeException
     */
    public function getByOID($oid)
    {
        if (!is_object($this->sessionManager)) {
            throw new \RuntimeException('The session manager has not been set');
        }

        $snmpInfo = $this->sessionManager->walk($oid);

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

            $type  = array_shift($pieces);
            $alias = implode('_', $pieces);

            if (isset($this->objects[$type][$alias])) {
                $result = $this->objects[$type][$alias];
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
     * @return Objects\Device|Device
     */
    public function getDevice()
    {
        return $this->device;
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
        $type = ucfirst($type);

        $aliasPieces = explode('_', $alias);

        foreach ($aliasPieces as $key => $piece) {
            $aliasPieces[$key] = ucfirst($piece);
        }

        $alias = implode('', $aliasPieces);

        return __NAMESPACE__ . '\\' . $this->objectsNamespace . '\\' . $type . '\\' . $alias;
    }
}
