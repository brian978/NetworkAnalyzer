<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Iface;

class Out extends AbstractIfaceHelper
{
    /**
     * Used to process a single array entry
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        $this->bindToInterfaceObject($data);
        $this->parentObject->setOut($this);

        $this->data = doubleval(trim(str_replace('Counter32: ', '', current($data))));

        return $this;
    }
}
