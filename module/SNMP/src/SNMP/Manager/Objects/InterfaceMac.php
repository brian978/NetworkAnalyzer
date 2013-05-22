<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

class InterfaceMac extends AbstractObject
{
    /**
     * The method is used to process a string of data provided by the ObjectManager
     *
     * @param string $macString
     * @return mixed
     */
    public function process($macString)
    {
        $this->setInfo(trim(str_replace('Hex-STRING: ', '', $macString)));

        return $this;
    }
}