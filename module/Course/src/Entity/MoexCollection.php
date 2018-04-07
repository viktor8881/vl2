<?php

namespace Course\Entity;


class MoexCollection extends \ArrayObject
{

    public function getTradeDateTime()
    {
        /** @var $moex Moex */
        $moex = $this->offsetGet(0);
        return $moex->getTradeDateTime();
    }

}