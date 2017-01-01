<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 31.12.2016
 * Time: 1:53
 */

namespace Model\Exchange\Filter;

use Model\AbstractCriterion;

class EqualsType extends AbstractCriterion
{

    protected $field = 'type';

    public function getField()
    {
        return $this->field;
    }


}