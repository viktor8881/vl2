<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 01.01.2017
 * Time: 12:50
 */

namespace Model;




abstract class AbstractCriterion
{

    private $values;

    public function __construct(array $values = null)
    {
        if ($values) {
            $this->values = $values;
        }
    }

    public function add($value)
    {
        if ($value) {
            $this->values[] = $value;
        }
        return $this;
    }

    public function getValue()
    {
        return $this->values;
    }

}
