<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager;

use SNMP\Model\Session;

class SessionManager
{
    /**
     * @var \SNMP\Model\Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Proxy method for the session class
     *
     * @param       $name
     * @param array $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments = array())
    {
        return $this->session->$name($arguments);
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