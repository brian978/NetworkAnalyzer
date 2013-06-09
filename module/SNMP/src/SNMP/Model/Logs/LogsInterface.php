<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Model\Logs;

use Library\Entity\EntityInterface;

interface LogsInterface
{
    public function setLimit($limit);

    public function save(EntityInterface $object);
}
