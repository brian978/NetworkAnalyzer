<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Design\Library;

use Zend\View\Model\ViewModel;

class Element extends \ArrayIterator
{
    public function __construct(ViewModel $viewModel, $id = '')
    {
        parent::__construct(array(
            'viewModel' => $viewModel,
            'id' => $id
        ));
    }

    /**
     * @param $id
     * @return \Design\Library\Element
     */
    public function setId($id)
    {
        if (is_string($id) || is_numeric($id))
        {
            $this->offsetSet('id', $id);
        }

        return $this;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $val = null;

        if ($this->offsetExists($name))
        {
            $val = $this->offsetGet($name);
        }

        return $val;
    }
}