<?php

namespace Base\Queue;


class QueueCollection extends \ArrayObject
{

    private $queueAdapter;

    /**
     * @param mixed $queueAdapter
     * @return QueueCollection
     */
    public function setQueueAdapter($queueAdapter)
    {
        $this->queueAdapter = $queueAdapter;
        return $this;
    }

    /**
     * @param string $name
     * @return Queue
     */
    public function getByName($name)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }
        $queue = new Queue($this->queueAdapter, $name);
        $this->offsetSet($name, $queue);
        return $queue;
    }

}