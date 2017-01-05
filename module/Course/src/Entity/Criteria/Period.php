<?php

namespace Course\Entity\Criteria;

use Doctrine\Common\Collections\Criteria;

class Period
{
    private $start;
    private $end;

    public function __construct(\DateTime $start, \DateTime $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function getCriterion()
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->gte("date_create", $this->start->format(DATE_ISO8601)))
            ->andWhere(Criteria::expr()->lte("date_create", $this->end->format(DATE_ISO8601)));
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

}