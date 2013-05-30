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

class Contact extends AbstractProcessorObject
{

    /**
     * Used to process a single array entry
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        $this->parentObject->setContact($this);

        $this->data = trim(str_replace('STRING: ', '', current($data)));
        $this->data = str_replace('"', '', $this->data);

        return $this;
    }
}
