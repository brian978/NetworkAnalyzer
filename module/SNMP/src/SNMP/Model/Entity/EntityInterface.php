<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Model\Entity;

interface EntityInterface
{
    public function getSnmpVersion();

    public function getSnmpCommunity();

    public function getHostname();
}