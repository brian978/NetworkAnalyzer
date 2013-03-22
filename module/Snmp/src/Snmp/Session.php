<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Snmp;

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
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
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

    public function __invoke()
    {
        return $this->session;
    }

    /**
     * Creates the SNMP session
     *
     * @return \Snmp\Session
     * @throws \RuntimeException
     */
    public function open()
    {
        if ($this->session instanceof \SNMP === false)
        {
            if ($this->serviceManager instanceof ServiceManager === false)
            {
                throw new \RuntimeException('The service manager has not been set');
            }

            /** @var $config \Zend\Config\Config */
            $config = $this->serviceManager->get('Config');
            $config = $config['snmp'];

            $this->session = new \SNMP(
                $config['version'],
                $config['hostname'],
                $config['community']
            );
        }

        return $this;
    }

    public function close()
    {
        $this->session->close();
    }

    /**
     * @param string $object_id
     * @throws \RuntimeException
     * @return mixed
     */
    public function get($object_id)
    {
        $output = $this->session->get($object_id);

        if($output == false)
        {
            throw new \RuntimeException($this->session->getError());
        }
    }
}