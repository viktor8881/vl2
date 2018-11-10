<?php

namespace Analysis\Service;


use Base\Entity\CriterionCollection;
use Course\Entity\Course;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Service\CourseManager;
use Exchange\Entity\Exchange;

class MovingAverage
{

    const STATUS_NULL = 0;
    const STATUS_CROSS_UP = 1;
    const STATUS_CROSS_DOWN = 2;


    private $courseManager;

    public function __construct(CourseManager $courseManager)
    {
        $this->courseManager = $courseManager;
    }

    /**
     * @param array $values
     * @param int   $during
     *
     * @return array
     */
    public function listAvg(array $values, $during = 14)
    {
        $result = [];
        for ($i = 1; $i <= count($values); $i++) {
            $offset = $i > $during ? $i - $during : 0;
            $slice = array_slice($values, $offset, $during);
            $result[] = array_sum($slice) / count($slice);
        }
        return $result;
    }

    /**
     * @param Course[] $courses
     * @param int   $during
     *
     * @return array
     */
    public function listAvgByCourses(array $courses, $during = 14)
    {
        $values = [];
        foreach ($courses as $course) {
            $values[] = $course->getValue();
        }
        return $this->listAvg($values, $during);
    }


    public function getStatusCrossByExchangeAndDate(Exchange $exchange, \DateTime $date, $during = 9)
    {
        $dateStart = clone $date;
        $dateStart->sub(new \DateInterval('P' . $during * 3 . 'D'));
        $criteria = new CriterionCollection();
        $criteria->append(new CriterionExchange($exchange));
        $criteria->append(new CriterionPeriod([$dateStart, $date]));
        /** @var Course[] $courses */
        $courses = $this->courseManager->fetchAllByCriterionsOnUniqDate($criteria);

        $status = self::STATUS_NULL;
        if (count($courses)) {
            $courseEnd = end($courses)->getValue();
            $coursePrev = prev($courses)->getValue();

            $listAvg = $this->listAvgByCourses($courses, $during);

            $listAvgEnd = end($listAvg);
            $listAvgPrev = prev($listAvg);

            if ($coursePrev < $listAvgPrev && $courseEnd > $listAvgEnd) {
                $status = self::STATUS_CROSS_UP;
            } elseif ($coursePrev > $listAvgPrev && $courseEnd < $listAvgEnd) {
                $status = self::STATUS_CROSS_DOWN;
            }
        }
        return $status;
    }

}