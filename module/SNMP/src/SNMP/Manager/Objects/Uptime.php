<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

class Uptime extends AbstractObject
{
    /**
     * The method is used to process a string of data provided by the ObjectManager
     *
     * @param string $uptimeString
     * @return mixed
     */
    public function process($uptimeString)
    {
        // The uptime should start after the first 2 entries
        $uptimePieces = explode(' ', $uptimeString);
        unset($uptimePieces[0]);
        unset($uptimePieces[1]);

        $this->setInfo(trim(implode(' ', $uptimePieces)));

        return $this;
    }
}