<?php

namespace Base\Entity;

use Zend\Stdlib\ArrayObject;

class OrderCollection extends ArrayObject
{

    public function toArray()
    {
        $result = [];
        foreach ($this->getIterator() as $criterion) {
            $result[$criterion->getFieldName()] = $criterion->getValues();
        }
        return $result;
    }

}