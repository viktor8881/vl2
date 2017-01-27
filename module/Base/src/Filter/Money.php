<?php
namespace Base\Filter;

use Zend\Filter\FilterInterface;

class Money implements FilterInterface
{

    public function filter($value)
    {        
        $value = str_replace(',', '.', $value);        
        return $value;
    }

}