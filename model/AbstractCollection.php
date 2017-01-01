<?php

namespace Model;


abstract class AbstractCollection implements \IteratorAggregate, \Countable, \ArrayAccess
{

    protected $values = array();


    public function __construct(array $values = null)
    {
        if ($values) {
            $this->values = $values;
        }
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->values;
    }

    /**
     * @param $key
     * @param mixed $value
     * @return $this
     */
    protected function addValue($key, $value)
    {
        if (!is_null($key) or is_string($key)) {
            $this->values[$key] = $value;
        } else {
            $this->values[] = $value;
        }
        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isExistsKey($key)
    {
        if (isset($this->values[$key])) {
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function getValue($key)
    {
        if ($this->isExistsKey($key)) {
            return $this->values[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @return $this
     */
    protected function removeByKey($key)
    {
        if ($this->isExistsKey($key)) {
            unset($this->values[$key]);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        reset($this->values);
        return current($this->values);
    }

    /**
     * @return $this
     */
    protected function clear()
    {
        $this->values = array();
        return $this;
    }

    /**
     * @return array
     */
    public function getArrayKeys()
    {
        return array_keys($this->values);
    }

    /**
     * @return mixed|null
     */
    public function last()
    {
        $i = 0;
        $count = count($this->values);
        foreach ($this->values as $key => $item) {
            if (++$i == $count) {
                return $this->values[$key];
            }
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function first()
    {
        foreach ($this->values as $key => $item) {
            return $this->values[$key];
        }
        return null;
    }

    public function __clone()
    {
        foreach ($this->values as $key => $item) {
            if (is_object($item)) {
                $this->values[$key] = clone $item;
            }
        }
    }


    /**
     * =================== for use as array =========================
     */

    public function offsetSet($offset, $value)
    {
        return $this->addValue($offset, $value);
    }

    public function offsetExists($offset)
    {
        return $this->isExistsKey($offset);
    }

    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getValue($offset);
    }


    abstract public function add($item);

    abstract public function remove($item);


}
