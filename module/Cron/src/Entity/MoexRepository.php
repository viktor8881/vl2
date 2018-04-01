<?php

namespace Cron\Entity;


class MoexRepository extends \ArrayObject
{

    public function getTradeDateTime()
    {
        /** @var $moex Moex */
        $moex = $this->offsetGet(0);
        return $moex->getTradeDateTime();
    }

}