<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Library\Object\ArrayObject\Helper;

use Zend\Stdlib\Hydrator\ClassMethods;

class ObjectConverter
{
    /**
     * @var \ArrayObject
     */
    protected $source;

    /**
     * @var object
     */
    protected $dest;

    /**
     * @param \ArrayObject $source
     * @param \stdClass    $dest
     */
    public function __construct(\ArrayObject $source, $dest)
    {
        $this->source = $source;
        $this->dest   = $dest;
    }

    public function convert()
    {
        $hydrator = new ClassMethods();
        $data     = $this->source->getArrayCopy();
        $newData  = array();

        // Converting the key names
        foreach ($data as $key => $value) {
            if (strpos($key, '_') !== false) {
                $pieces = explode('_', $key);

                array_walk(
                    $pieces,
                    function (&$value, $index) {
                        if ($index > 0) {
                            $value = ucfirst($value);
                        }
                    }
                );

                $newData[implode('', $pieces)] = $value;
            }
        }

        // Hydrating the object
        $this->dest = $hydrator->hydrate($data, $this->dest);

        return $this->dest;
    }
}
