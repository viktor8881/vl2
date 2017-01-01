<?php

namespace Model;


class OrderCollection extends AbstractCollection
{

    public function __construct(array $orders = null)
    {
        if ($orders) {
            foreach ($orders as $order) {
                $this->add($order);
            }
        }
    }

    public function remove($item)
    {
        if (!($item instanceof AbstractOrder)) {
            throw new Exception("Item must be implements AbstractOrder.");
        }
        parent::removeByKey(get_class($item));
    }

    public function add($item)
    {
        if (!($item instanceof AbstractOrder)) {
            throw new Exception("Item must be implements AbstractOrder.");
        }
        parent::addValue(get_class($item), $item);
        return $this;
    }

    public function getOrders()
    {
        return parent::getIterator();
    }

}
