<?php

namespace Base\Entity;

use Zend\Filter\StaticFilter;

/**
 * Class AbstractEntity
 *
 * @package Base\Entity
 */
abstract class AbstractEntity implements IEmpty
{

    private $filterMethodName;

    /**
     * AbstractEntity constructor.
     *
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = array())
    {
        $this->filterMethodName = new \Zend\Filter\Word\UnderscoreToCamelCase();
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = $this->getMethodName($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * @param $key
     *
     * @return string
     */
    private function getMethodName($key)
    {
        $key = $this->filterMethodName->filter($key);
        return 'set' . ucfirst($key);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [];
    }

    /**
     * @return mixed
     */
    abstract public function getId();

}
