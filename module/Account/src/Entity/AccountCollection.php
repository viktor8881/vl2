<?php

namespace Account\Entity;

use Exchange\Entity\Exchange;
use Zend\Stdlib\ArrayObject;

class AccountCollection extends ArrayObject
{

    /**
     * @param Exchange $exchange
     * @return float|int
     */
    public function getBalanceByExchange(Exchange $exchange)
    {
        $result = 0;
        /** @var Account $account */
        foreach ($this->getIterator() as $account) {
            if ($account->getExchange() == $exchange) {
                $result = $account->getBalance();
                break;
            }
        }
        return $result;
    }


}