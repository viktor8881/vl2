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

    public function isQuotesGrowth()
    {
        $list = $this->getCourses();
        return current($list) > end($list);
    }

    public function isQuotesFall()
    {
        $list = $this->getCourses();
        return current($list) < end($list);
    }

    public function countData()
    {
        $list = $this->getCourses();
        return count($list);
    }

    public function getDiffPercent()
    {
        $list = $this->getCourses();
        return 100 - (abs(reset($list) * 100 / end($list)));
    }

}