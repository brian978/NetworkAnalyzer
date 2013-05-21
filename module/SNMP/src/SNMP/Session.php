<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Session implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \SNMP
     */
    protected $session;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @param ServiceManager $serviceManager
     * @param                $config
     */
    public function __construct(ServiceManager $serviceManager, $config)
    {
        $this->config = $config;

        $this->setServiceManager($serviceManager);
        $this->open();
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function __call($name, $arguments = array())
    {
        $result = null;

        if (is_callable(array($this->session, $name))) {
            $result = call_user_func_array(
                array($this->session, $name),
                $arguments
            );
        }

        return $result;
    }

    public function __invoke()
    {
        return $this->session;
    }

    /**
     * Creates the SNMP session
     *
     * @return \SNMP\Session
     * @throws \RuntimeException
     */
    public function open()
    {
        if ($this->session instanceof \SNMP === false) {
            if ($this->serviceManager instanceof ServiceManager === false) {
                throw new \RuntimeException('The service manager has not been set');
            }

            $this->session = new \SNMP(
                $this->config['version'],
                $this->config['hostname'],
                $this->config['community']
            );
        }

        return $this;
    }

    public function close()
    {
        $this->session->close();
    }

    /**
     * @param string $objectId
     *
     * @throws \RuntimeException
     * @return mixed
     */
    public function get($objectId)
    {
        $output = $this->session->get($objectId);

        if ($output == false) {
            throw new \RuntimeException($this->session->getError());
        }
    }
}
