<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager;

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
     * Contains the available objects the manager can handle
     *
     * @var array
     */
    protected $objects = array(
        'uptime' => '.1.3.6.1.2.1.1.3',
        'contact' => '.1.3.6.1.2.1.1.4',
        'name' => '.1.3.6.1.2.1.1.5',
        'location' => '.1.3.6.1.2.1.1.6',
        'ip' => '.1.3.6.1.2.1.4.20',
        'interface_name' => '.1.3.6.1.2.1.2.2.1.2',
        'interface_mac' => '.1.3.6.1.2.1.2.2.1.6',
        // Octets
        'interface_in' => '.1.3.6.1.2.1.2.2.1.10',
        // Octets
        'interface_out' => '.1.3.6.1.2.1.2.2.1.16',
        // 1: up, 2: down, 3: testing
        'interface_admin_status' => '.1.3.6.1.2.1.2.2.1.7',
        // 1: up, 2: down, 3: testing, 4: unkown, 5: dormant, 6: notPresent, 7: lowerLayerDown
        'interface_status' => '.1.3.6.1.2.1.2.2.1.8',
        'interface_queue_length' => '.1.3.6.1.2.1.2.2.1.21'
    );

    /**
     * @param SessionManager $sessionManager
     */
    public function setSessionManager(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
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

        $className = $this->createClassName($name);
        $snmpInfo  = $this->sessionManager->walk($this->getOID($name));

        foreach ($snmpInfo as $info) {
            $object = new $className();
            $object->process($info);
            var_dump($object);
        }

        return $this;
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
     * @param $objectName
     * @return string
     */
    public function getOID($objectName)
    {
        $result = '';

        if (isset($this->objects[$objectName])) {
            $result = $this->objects[$objectName];
        }

        return $result;
    }

    /**
     * Creates a class name using a string
     *
     * @param $name
     * @return string
     */
    protected function createClassName($name)
    {
        $pieces = explode('_', $name);

        if (count($pieces) > 1) {
            foreach ($pieces as $key => $piece) {
                $pieces[$key] = ucfirst($piece);
            }

            $name = implode('', $pieces);
        } else {
            $name = ucfirst($name);
        }

        $name = __NAMESPACE__ . '\\' . $this->objectsNamespace . '\\' . $name;

        return $name;
    }
}