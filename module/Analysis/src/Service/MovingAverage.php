<?php

namespace Analysis\Service;


use Course\Entity\Course;

class MovingAverage
{

    /**
     * @param array $values
     * @param int   $during
     *
     * @return array
     */
    public static function listAvg(array $values, $during = 14)
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
    public static function listAvgByCourses(array $courses, $during = 14)
    {
        $values = [];
        foreach ($courses as $course) {
            $values[] = $course->getValue();
        }
        return self::listAvg($values, $during);
    }


}