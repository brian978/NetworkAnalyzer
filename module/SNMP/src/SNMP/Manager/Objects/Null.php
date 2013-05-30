<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace SNMP\Manager\Objects;

class Null implements ObjectProcessorInterface
{
    /**
     * @return string
     */
    public function get()
    {
        return '';
    }

    public function __invoke()
    {
        return $this->get();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->get());
    }

    /**
     * Used to process a single array entry
     *
     * @param array $data
     * @return mixed
     */
    public function process(array $data)
    {
        unset($data);
    }
}