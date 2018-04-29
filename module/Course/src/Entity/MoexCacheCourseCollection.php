<?php

namespace Course\Entity;

use Zend\Stdlib\ArrayObject;

class MoexCacheCourseCollection extends ArrayObject
{

    /**
     * @return bool
     */
    public function firstIsDownTrend() {
        /** @var MoexCacheCourse $item */
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
        /** @var MoexCacheCourse $item */
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
        /** @var MoexCacheCourse $item */
        $item = end($this->storage);
        return  ($item) ? true : false;
    }

    /**
     * @return int
     */
    public function countFirstData() {
        /** @var MoexCacheCourse $item */
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
        /** @var MoexCacheCourse $cacheCourse */
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
        /** @var MoexCacheCourse $cacheCourse */
        foreach ($this->getIterator() as $cacheCourse) {
            $result[] = $cacheCourse->getId();
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getFirstDate() {
        /** @var MoexCacheCourse $item */
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
        /** @var MoexCacheCourse $item */
        $item = end($this->storage);
        if ($item) {
            return $item->getLastDate();
        }
        return null;
    }

    /**
     * @return MoexCacheCourse[]
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