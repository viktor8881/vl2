<?php

namespace Analysis\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 */
class MoexOvertimeAnalysis extends MoexAnalysis
{

    public function getType()
    {
        return self::TYPE_OVER_TIME;
    }

    /**
     * @return Moex|null
     */
    public function getFirstCourse()
    {
        return $this->getCourses()->first();
    }

    /**
     * @return Moex|null
     */
    public function getLastCourse()
    {
        return $this->getCourses()->last();
    }

    /**
     * @return bool
     */
    public function isQuotesGrowth()
    {
        $first = $this->getFirstCourse();
        $last = $this->getLastCourse();
        return $first->getValue() < $last->getValue();
    }

    /**
     * @return bool
     */
    public function isQuotesFall()
    {
        $first = $this->getFirstCourse();
        $last = $this->getLastCourse();
        return $first->getValue() > $last->getValue();
    }

    /**
     * @return int
     */
    public function countData()
    {
        $list = $this->getCourses();
        return count($list);
    }

    /**
     * @return float
     */
    public function getDiffPercent()
    {
        $first = $this->getFirstCourse();
        $last = $this->getLastCourse();
        return (abs($first->getValue() - $last->getValue()) * 100) / $first->getValue();
    }

}