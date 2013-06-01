<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects\Iface;

use SNMP\Manager\Objects\NullObject;

class Ip extends AbstractIfaceHelper
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

        // Searching for the proper OID index to know that interface to bind to
        $interfaceIndexOid = $objectManager->getOID('ip_index');
        $interfaceOidIndex = $objectManager->getByOID($interfaceIndexOid . '.' . $this->data);
        $oidIndex          = Iface::extractOidIndex(current($interfaceOidIndex));

        $this->bindToInterfaceObject($oidIndex);

        // There might be an interface with sub-interfaces so we need to make sure
        // that an IP hasn't already been set to that interface
        if ($this->parentObject->getIp() instanceof NullObject) {
            $this->parentObject->setIp($this);
        } else {

            // Creating a new interface with all the characteristics
            // of the parent one and setting the IP to this one
            $newInterface = clone $this->parentObject;
            $newInterface->setIp($this);

            $this->parentObject->attachInterface($newInterface);
        }

        return $this;
    }
}
