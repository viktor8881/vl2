<?php
namespace Base\Filter;

use Zend\Filter\FilterInterface;

class ToFloat implements FilterInterface
{

    public function filter($value)
    {        
        $value = str_replace(',', '.', $value);        
        return $value;
    }

}