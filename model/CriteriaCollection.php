<?php

namespace Model;


class CriteriaCollection extends AbstractCollection
{

    public function __construct(array $orders = null)
    {
        if ($orders) {
            if (!is_array($orders)) {
                $orders = array($orders);
            }
            foreach ($orders as $filter) {
                $this->add($filter);
            }
        }
    }

    public function remove($item)
    {
        if (!($item instanceof AbstractCriterion)) {
            throw new Exception("Item must be implements AbstractCriterion.");
        }
        parent::removeByKey(get_class($item));
    }

    public function add($item)
    {
        if (!($item instanceof AbstractCriterion)) {
            throw new Exception("Item must be implements AbstractCriterion.");
        }
        /** @var AbstractCriterion $item */
        if ($item) {
            $key = get_class($item);
            $fItem = parent::getValue($key);
            if ($fItem) {
                $fItem->add($item->getValue());
            } else {
                parent::addValue($key, $item);
            }
        }
        return $this;
    }

    public function getFilters()
    {
        return parent::getIterator();
    }

}
