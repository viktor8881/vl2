<?php

namespace Core\Entity;

use Zend\Filter\StaticFilter;

abstract class AbstractEntity implements IEmpty
{

    public function __construct(array $options = null)
    {
        if ($options) {
            $this->setToArray($options);
        }
    }

    public function setToArray(array $options = array())
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = $this->getMethodName($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    private function getMethodName($key)
    {
        $key = StaticFilter::execute($key, 'Word_UnderscoreToCamelCase');
        return 'set' . ucfirst($key);
    }

    public function toArray()
    {
        return [];
    }

    abstract public function getId();

}
