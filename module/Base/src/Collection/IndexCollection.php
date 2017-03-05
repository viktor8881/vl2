<?php

namespace Base\Collection;


use Base\Entity\IEmpty;

class IndexCollection implements \IteratorAggregate, \Countable
{

    /** @var array */
    protected $_values = [];


    /**
     * @return \ArrayObject
     */
    public function getIterator()
    {
        return new \ArrayObject($this->_values);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isExistsKey($key)
    {
        return isset($this->_values[$key]);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param IEmpty $value
     * @return $this
     */
    public function add($key, IEmpty $value)
    {
        $this->_values[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove($key)
    {
        if (isset($this->_values[$key])) {
            unset($this->_values[$key]);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_values);
    }

    /**
     * @return IEmpty|null
     */
    public function current()
    {
        return current($this->_values);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->_values = [];
        return $this;
    }

    /**
     * @return IndexCollection
     */
    public function rewind() {
        $name = get_class($this);
        /** @var $result IndexCollection */
        $result = new $name;
        foreach (array_reverse($this->_values, true) as $key=>$item) {
            $result->add($key, $item);
        }
        return $result;
    }

    /**
     * @return int[]
     */
    public function getListId()
    {
        return array_keys($this->_values);
    }

    /**
     * @return IEmpty|null
     */
    public function first()
    {
        return reset($this->_values);
    }

    /**
     * @return IEmpty|null
     */
    public function last()
    {
        return end($this->_values);
    }

    /**
     * @return IEmpty
     */
    public function next()
    {
        return next($this->_values);
    }

    /**
     * @return IEmpty
     */
    public function key()
    {
        return key($this->_values);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return false === current($this->_values);
    }

    /**
     * @return IndexCollection
     */
    public function reverse()
    {
        $classCurrentName = get_class($this);
        /** @var $result IndexCollection */
        $result = new $classCurrentName;
        foreach (array_reverse($this->_values, true) as $key => $item) {
            $result->add($key, $item);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_values;
    }

    public function __clone()
    {
        foreach ($this->_values as $key => $item) {
            if (is_object($item)) {
                $this->_values[$key] = clone $item;
            }
        }
    }

}