<?php
namespace Base\Entity;

abstract class AbstractCriterion
{
    protected $fieldName;
    protected $values;

    public function __construct($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->values = $values;
    }

    public function add($value)
    {
        $this->values[] = $value;
    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param mixed $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    public function getFirstValue()
    {
        if (count($this->values)) {
            return reset($this->values);
        }
        return null;
    }

    public function getSecondValue()
    {
        if (count($this->values)) {
            reset($this->values);
            return isset($this->values[1]) ? $this->values[1] : null;
        }
        return null;
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    public function countValue()
    {
        return count($this->values);
    }

}