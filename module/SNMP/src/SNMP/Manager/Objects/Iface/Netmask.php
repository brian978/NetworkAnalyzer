<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Iface;

class Netmask extends AbstractIfaceHelper
{
    protected $oid = 'iso.3.6.1.2.1.4.20.1.3';

    /**
     * Used to process a single array entry
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        $ipAddress  = str_replace($this->oid . '.', '', key($data));
        $this->data = trim(str_replace('IpAddress: ', '', current($data)));

        /** @var $device \SNMP\Manager\Objects\Device\Device */
        $device = $this->getParentObject();

        /** @var $interface \SNMP\Manager\Objects\Iface\Iface */
        $interface = $device->getInterfaceByIP($ipAddress);

        if ($interface != null) {
            $interface->setNetmask($this);
            $this->bindToInterfaceObject($interface->getOidIndex());
        }

        return $this;
    }
}