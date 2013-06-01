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

class TcpConnection extends AbstractObject
{
    /**
     * @var \SNMP\Manager\Objects\Tcp\LocalAddress
     */
    protected $localAddress;

    /**
     * @var \SNMP\Manager\Objects\Tcp\RemoteAddress
     */
    protected $remoteAddress;
}