<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Tcp;

use SNMP\Manager\Objects\AbstractObject;

class Connection extends AbstractObject
{
    protected $oid = 'iso.3.6.1.2.1.6.13.1.2';

    /**
     * @var string
     */
    protected $localAddress;

    /**
     * @var string
     */
    protected $remoteAddress;

    /**
     * @var string
     */
    protected $localPort;

    /**
     * @var string
     */
    protected $remotePort;

    /**
     * @param array $data
     * @return $this
     */
    public function process(array $data)
    {
        $oidData = key($data);
        $ipData  = str_replace($this->oid . '.', '', $oidData);
        $pieces  = explode('.', $ipData);

        $this->localAddress  = implode('.', array($pieces[0], $pieces[1], $pieces[2], $pieces[3]));
        $this->localPort     = $pieces[4];
        $this->remoteAddress = implode('.', array($pieces[5], $pieces[6], $pieces[7], $pieces[8]));
        $this->remotePort    = $pieces[9];

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $value = null;

        if (property_exists($this, $name)) {
            $value = $this->$name;
        }

        return $value;
    }
}
