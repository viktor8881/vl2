<?php
namespace Core\Entity;

use Zend\Stdlib\ArrayObject;

class CriterionCollection extends ArrayObject
{

    public function toArray()
    {
        $result = [];
        /** @var CriterionAbstract $criterion */
        foreach ($this->getIterator() as $criterion) {
            $result[$criterion->getFieldName()] = $criterion->getValues();
        }
        return $result;
    }

}