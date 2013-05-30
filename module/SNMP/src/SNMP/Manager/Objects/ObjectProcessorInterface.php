<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

interface ObjectProcessorInterface
{
    public function process(array $data);

    public function get();

    public function __invoke();

    public function __toString();
}
