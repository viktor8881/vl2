<?php

namespace Course\Entity;

use Zend\Stdlib\ArrayObject;

class CacheCourseCollection extends ArrayObject
{

    /**
     * @return bool
     */
    public function firstIsDownTrend() {
        /** @var CacheCourse $item */
        $item = current($this->storage);
        if ($item) {
            return $item->isDownTrend();
        }
        return false;
    }

    /**
     * @return bool
     */
    public function firstIsUpTrend() {
        /** @var CacheCourse $item */
        $item = current($this->storage);
        if ($item) {
            return $item->isUpTrend();
        }
        return false;
    }

    /**
     * @return bool
     */
    public function lastNullOperation() {
        /** @var CacheCourse $item */
        $item = end($this->storage);
        return  ($item) ? true : false;
    }

    /**
     * @return int
     */
    public function countFirstData() {
        /** @var CacheCourse $item */
        $item = current($this->storage);
        if ($item) {
            return $item->countDataValue();
        }
        return 0;
    }

    /**
     * @return array
     */
    public function listLastValue() {
        $result = [];
        /** @var CacheCourse $cacheCourse */
        foreach ($this->getIterator() as $cacheCourse) {
            $result[] = $cacheCourse->getLastValue();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function listId() {
        $result = [];
        /** @var CacheCourse $cacheCourse */
        foreach ($this->getIterator() as $cacheCourse) {
            $result[] = $cacheCourse->getId();
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getFirstDate() {
        /** @var CacheCourse $item */
        $item = current($this->storage);
        if ($item) {
            return $item->getFirstDate();
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getLastDate() {
        /** @var CacheCourse $item */
        $item = end($this->storage);
        if ($item) {
            return $item->getLastDate();
        }
        return null;
    }

    /**
     * @return CacheCourse[]
     */
    public function getList()
    {
        $result = [];
        foreach ($this->getIterator() as $item) {
            $result[] = $item;
        }
        return $result;
    }

}