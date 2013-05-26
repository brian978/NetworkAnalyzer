<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Device;

use SNMP\Manager\Objects\AbstractProcessorObject;

/**
 * Class Uptime
 *
 * @package SNMP\Manager\Objects\Device
 */
class Uptime extends AbstractProcessorObject
{
    /**
     * @param array $data
     * @return $this|mixed
     */
    public function process(array $data)
    {
        $this->parentObject->setUptime($this);

        $data = current($data);

        // The uptime should start after the first 2 entries
        $uptimePieces = explode(' ', $data);
        unset($uptimePieces[0]);
        unset($uptimePieces[1]);

        $this->data = trim(implode(' ', $uptimePieces));

        return $this;
    }
}
