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
use Zend\View\Renderer\RendererInterface;

class Components
{
    /**
     * Contains an array of design elements
     *
     * @var array
     */
    protected $components = array();

    /**
     * Contains an array of queues indexed by the position of the element
     *
     * @var array
     */
    protected $queues = array();

    /**
     * An identifier for the default position in the page
     *
     * @var string
     */
    protected $defaultPosition = 'default';

    /**
     * Identifies the class that will be used for the queue
     *
     * @var string
     */
    protected $queueClass = '\SplPriorityQueue';

    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $renderer = null;

    /**
     * @param mixed $position
     * @return \Design\Library\Components
     */
    public function setDefaultPosition($position)
    {
        if (is_string($position) || is_numeric($position))
        {
            $this->defaultPosition = $position;
        }

        return $this;
    }

    /**
     * @param \Zend\View\Renderer\RendererInterface $renderer
     * @return Components
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @param \Zend\View\Model\ViewModel $viewModel
     * @param string                     $id
     * @param                            $priority
     * @param                            $position
     * @return Element
     */
    public function createElement(
        ViewModel $viewModel,
        $id = '',
        $priority,
        $position
    ) {
        $element = new Element($viewModel, $id);

        $this->add($element, $priority, $position);

        return $this;
    }

    /**
     * @param \Design\Library\Element $element The position can be either numeric or a string
     * @param int                     $priority
     * @param string                  $position
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return \Design\Library\Components
     */
    public function add(Element $element, $priority = 0, $position = '')
    {
        if (!is_string($position) && !is_numeric($position) && !is_numeric($priority))
        {
            throw new \InvalidArgumentException('The position must be a string or a number and the priority must be numeric');
        }

        if (empty($position))
        {
            $position = $this->defaultPosition;
        }

        /**
         * --------------------------------
         * COMPONENT HANDLING
         * --------------------------------
         */
        // Initializing the components array entry
        if (!isset($this->components[$position]))
        {
            $this->components[$position] = array();
        }

        // Registering the element in the components array only if it has an ID
        if (!empty($element['id']))
        {
            if (isset($this->components[$position][$element['id']]))
            {
                throw new \RuntimeException('Element ID is already set');
            }

            $this->components[$position][$element['id']] = array(
                'object' => $element,
                'priority' => $priority
            );
        }

        /**
         * --------------------------------
         * QUEUE HANDLING
         * --------------------------------
         */
        // Initializing the queues array entry
        if (!isset($this->queues[$position]))
        {
            $this->queues[$position] = new $this->queueClass;
        }

        /** @var $queue \SplPriorityQueue */
        $queue = $this->queues[$position];

        // Registering the element in the queue
        $queue->insert($element, $priority);

        return $this;
    }

    /**
     * @param mixed $elementId
     * @param mixed $position
     * @param       $elementId
     * @param       $position
     * @throws \InvalidArgumentException
     * @return Components
     */
    public function remove($elementId, $position)
    {
        if (!is_string($position) && !is_numeric($position))
        {
            throw new \InvalidArgumentException('The position must be a string or a number');
        }

        if (!is_string($elementId) && !is_numeric($elementId))
        {
            throw new \InvalidArgumentException('The element ID must be a string or a number');
        }

        // Need to remove the element from the components first so that
        // the queue can be rebuilt properly
        if (isset($this->components[$position][$elementId]))
        {
            unset($this->components[$position][$elementId]);

            $this->rebuildQueue($position);
        }

        return $this;
    }

    /**
     * The method is used when an element is deleted from the components array
     *
     * @param $position
     * @throws \InvalidArgumentException
     * @return Components
     */
    public function rebuildQueue($position)
    {
        if (!is_string($position) && !is_numeric($position))
        {
            throw new \InvalidArgumentException('The position must be a string or a number');
        }

        // Resetting the queue first
        /** @var $queue \SplPriorityQueue */
        $this->queues[$position] = $queue = new $this->queueClass;

        // Rebuilding the entire queue with the priorities and all
        foreach ($this->components[$position] as $data)
        {
            $queue->insert($data['object'], $data['priority']);
        }

        return $this;
    }

    /**
     * Checks if an ID is already set in a certain position
     *
     * @param mixed $elementId
     * @param mixed $position
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function isIdTaken($elementId, $position)
    {
        if (!is_string($position) && !is_numeric($position))
        {
            throw new \InvalidArgumentException('The position must be a string or a number');
        }

        if (!is_string($elementId) && !is_numeric($elementId))
        {
            throw new \InvalidArgumentException('The element ID must be a string or a number');
        }

        $result = false;

        if (isset($this->components[$position][$elementId]))
        {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns the number of elements for a position
     *
     * @param $position
     * @return int
     * @throws \InvalidArgumentException
     */
    public function has($position)
    {
        if (!is_string($position) && !is_numeric($position))
        {
            throw new \InvalidArgumentException('The position must be a string or a number');
        }

        $count = 0;

        if (isset($this->queues[$position]))
        {
            /** @var $queue \SplPriorityQueue */
            $queue = $this->queues[$position];
            $count = $queue->count();
        }

        return $count;
    }

    /**
     * @param mixed $position
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return string
     */
    public function render($position = null)
    {
        if (!is_object($this->renderer))
        {
            throw new \RuntimeException('The renderer has not been set');
        }

        if ($position !== null)
        {
            if (!is_string($position) && !is_numeric($position))
            {
                throw new \InvalidArgumentException('The position must be a string or a number');
            }
        }

        $rendered = '';

        if ($position == null)
        {
            /** @var $queue \SplPriorityQueue */
            foreach ($this->queues as $queue)
            {
                while ($queue->valid())
                {
                    $rendered .= $this->renderer->render($queue->current()->viewModel);

                    $queue->next();
                }

                // Resetting the queue pointer in case it needs to be rendered again
                $queue->rewind();
            }
        }
        else if (isset($this->queues[$position]))
        {
            /** @var $queue \SplPriorityQueue */
            $queue = $this->queues[$position];

            while ($queue->valid())
            {
                $rendered .= $this->renderer->render($queue->current()->viewModel);

                $queue->next();
            }

            // Resetting the queue pointer in case it needs to be rendered again
            $queue->rewind();
        }

        return $rendered;
    }

    /**
     * @return array
     */
    public function getQueues()
    {
        return $this->queues;
    }
}