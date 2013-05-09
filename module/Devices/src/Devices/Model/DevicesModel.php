<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Devices\Model;

use Devices\Entity\Device;

class DevicesModel
{
    public function save(Device $device)
    {
        if($device->getId() === 0)
        {
            $this->insert($device);
        }
        else
        {
            $this->update($device);
        }
    }

    protected function insert(Device $device)
    {

    }

    protected function update(Device $device)
    {

    }
}