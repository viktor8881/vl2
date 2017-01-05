<?php

namespace Course\Entity\Criteria;

use Doctrine\Common\Collections\Criteria;

class ExchangeId
{
    private $values;

    public function __construct($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->values = $values;
    }

    public function getCriterion()
    {
        return Criteria::create()
            ->where(Criteria::expr()->in("id", $this->values));
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

}