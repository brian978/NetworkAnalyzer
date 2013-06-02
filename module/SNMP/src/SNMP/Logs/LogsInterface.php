<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Logs;

use Library\Entity\AbstractEntity;

interface LogsInterface
{
    public function save(AbstractEntity $object);
}