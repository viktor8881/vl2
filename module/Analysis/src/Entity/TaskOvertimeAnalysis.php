<?php

namespace Analysis\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaskOvertimeAnalysis extends TaskAnalysis
{

    public function getType()
    {
        return self::TYPE_OVER_TIME;
    }

    /**
     * @return Course|null
     */
    public function getFirstCourse()
    {
        return $this->getCourses()->first();
    }

    /**
     * @return Course|null
     */
    public function getLastCourse()
    {
        return $this->getCourses()->last();
    }


    public function isQuotesGrowth()
    {
        $first = $this->getFirstCourse();
        $last = $this->getLastCourse();
        return $first->getValue() < $last->getValue();
    }

    public function isQuotesFall()
    {
        $first = $this->getFirstCourse();
        $last = $this->getLastCourse();
        return $first->getValue() > $last->getValue();
    }

    public function countData()
    {
        $list = $this->getCourses();
        return count($list);
    }

    public function getDiffPercent()
    {
        $first = $this->getFirstCourse();
        $last = $this->getLastCourse();
        return 100 - (abs($last->getValue() * 100 / $first->getValue()));
    }

}