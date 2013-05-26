<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Iface;

use SNMP\Manager\Objects\AbstractProcessorObject;
use SNMP\Manager\Objects\Device\Device;

class Ip extends AbstractProcessorObject
{

    /**
     * Used to process a single array entry
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        /** @var $objectManager \SNMP\Manager\ObjectManager */
        $objectManager = $this->parentObject->getParentObject();

        $this->data = trim(str_replace('IpAddress: ', '', current($data)));

        $interfaceIndexOid = $objectManager->getOID('iface_ip_index');
        $interfaceOidIndex = $objectManager->getByOID($interfaceIndexOid . '.' . $this->data);
        $oidIndex          = Iface::extractOidIndex(current($interfaceOidIndex));

        $this->bindToInterfaceObject($oidIndex);
        $this->parentObject->setIp($this);

        return $this;
    }
}