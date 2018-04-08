<?php

namespace Course\Entity;

use Zend\Stdlib\ArrayObject;

class CourseCollection extends ArrayObject
{

    /**
     * @return array
     */
    public function listValues()
    {
        $result = [];
        foreach ($this->getIterator() as $course) {
            $result[] = $course->getValue();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function listExchangeUpOrDown()
    {
        $result = [];
        if ($this->count() > 1) {
            $sign = null;
            $prev = $this->getIterator()->current();
            $i=0;
            /** @var Course $row */
            foreach ($this->getIterator() as $row) {
                if (++$i == 1) {
                    $result[] = $row;
                    continue;
                }
                if (is_null($sign)) {
                    if ($prev->getValue() > $row->getValue()) {
                        $sign = 'isGreater';
                    }elseif($prev->getValue() < $row->getValue()){
                        $sign = 'isLess';
                    }else{
                        break;
                    }
                    $result[] = $row;
                    $prev = $row;
                    continue;
                }
                if ( $this->{$sign}($prev->getValue(), $row->getValue()) ) {
                    $result[] = $row;
                    $prev = $row;
                    continue;
                }else{
                    break;
                }
            }
        }
        return $result;
    }

    public function toArray()
    {
        return $this->storage;
    }


    private function isGreater($left, $right) {
        return $left > $right;
    }

    private function isLess($left, $right) {
        return $left < $right;
    }

}