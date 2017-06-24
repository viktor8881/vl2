<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 25.05.2017
 * Time: 20:37
 */

namespace Cron\Service;


use Analysis\Service\MovingAverage;
use Course\Entity\Course;
use Course\Entity\CourseCollection;

class ServiceExp1
{
    /** @var Course[] */
    private $courses = [];
    /** @var [] */
    private $trends = [];



    public function init(CourseCollection $courses)
    {
        $movingAverage = new MovingAverage();
        $this->courses = $courses->toArray();
        foreach ($movingAverage->listAvgByCourses($this->courses, 14) as $i => $value) {
            $this->trends[$this->courses[$i]->getDateFormatDMY()] = $value;
        }
    }

    /**
     * @return int
     */
    public function countCourses()
    {
        return count($this->courses);
    }

    /**
     * @return int
     */
    public function countTrend()
    {
        return count($this->trends);
    }

    /**
     * @return bool
     */
    public function isDownTrend()
    {
        $values = array_values(array_reverse($this->trends));
        if ($values[0] > $values[1]) {
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function countLastDownTrend()
    {
        $result = 0;
        $count = count($this->trends);
        $trends = array_values($this->trends);
        $nextCourseValue = $trends[$count-1];
        for ($i = $count - 2; $i--; $i <=0) {
            if ($trends[$i] > $nextCourseValue) {
                $result++;
                $nextCourseValue = $trends[$i];
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isUpTrend()
    {
        $values = array_values(array_reverse($this->trends));
        if ($values[0] > $values[1]) {
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function countLastUpTrend()
    {
        $result = 0;
        $count = count($this->trends);
        $trends = array_values($this->trends);
        $nextCourseValue = $trends[$count-1];
        for ($i = $count - 2; $i--; $i <=0) {
            if ($trends[$i] < $nextCourseValue) {
                $result++;
                $nextCourseValue = $trends[$i];
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * @return Course
     */
    public function getLastCourse()
    {
        return end($this->courses);
    }

    /**
     * предыдущ
     * @return Course
     */
    public function getPenultimateCourse()
    {
        end($this->courses);
        return prev($this->courses);
    }

    /**
     * @return float
     */
    public function getLastCourseValue()
    {
        return $this->getLastCourse()->getValue();
    }

    /**
     * @return mixed
     */
    public function getLastTrendValue()
    {
        return end($this->trends);
    }

    /**
     * 7 последних точек изменения тренда
     * @return array
     */
    public function getLast7ValuesChangeTrend()
    {        
        $result = [];
        if (count($this->courses) > 1) {
            $sign = null;
            $prev = reset($this->courses);
            $i=0;
            foreach ($this->courses as $row) {
                if (++$i == 1) {
                    $result[$row->getDateFormatDMY()] = $row->getValue();
                    continue;
                }
                if (is_null($sign)) {
                    if ($prev->getValue() > $row->getValue()) {
                        $sign = 'isGreater';
                    } else {
                        $sign = 'isLess';
                    }
                    $prev = $row;
                    continue;
                }
                if ( !$this->{$sign}($prev->getValue(), $row->getValue()) ) {                
                    $result[$prev->getDateFormatDMY()] = $prev->getValue();
                    $sign = ($sign === 'isGreater') ? 'isLess' : 'isGreater';
                }
                $prev = $row;                
            }
            $result[$row->getDateFormatDMY()] = $row->getValue();
        }
        return array_splice($result, -7);
    }

    /**
     * @param array $values
     * @param float   $percent
     * @return bool
     */
    public function isCrossUpTrend(array $values, $percent = 0)
    {
        $percentFactor = 1 + ($percent / 100);
        // находим что это последнее значение впервые пересекла линию тренда
        end($this->courses);
        $predLastCourse = prev($this->courses);
        if ($predLastCourse->getValue() < $this->trends[$predLastCourse->getDateFormatDMY()] * $percentFactor) {
            foreach ($values as $date => $value) {
                if ($value > $this->trends[$date] * $percentFactor) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isDontFirstCrossUpTrend(array $values, $percent = 0)
    {
        $percentFactor = 1 + ($percent / 100);
        // находим что это последнее значение впервые пересекла линию тренда
        end($this->courses);
        $predLastCourse = prev($this->courses);
        if ($predLastCourse->getValue() > $this->trends[$predLastCourse->getDateFormatDMY()] * $percentFactor) {
            foreach ($values as $date => $value) {
                if ($value > $this->trends[$date] * $percentFactor) {
                    return true;
                }
            }
        }
        return false;
    }



    // exp function

    public function isDoubleBottom($values)
    {
        $values = array_values($values);
        if ($values[0] > $values[2] &&
            $values[4] > $values[2] &&
            $values[2] > $values[1] &&
            $values[2] > $values[3]) {
            return true;
        }
        return false;
    }

    public function isTripleBottom($values)
    {
        $values = array_values($values);
        if ($values[0] > $values[2] &&
            $values[0] > $values[4] &&
            $values[6] > $values[2] &&
            $values[6] > $values[4] &&
            $values[2] > $values[1] &&
            $values[2] > $values[3] &&
            $values[2] > $values[5] &&
            $values[4] > $values[1] &&
            $values[4] > $values[3] &&
            $values[4] > $values[5]) {
            return true;
        }
        return false;
    }

    public function isReverseHeadShoulders($values)
    {
        $values = array_values($values);
        if ($values[0] > $values[2] &&
            $values[0] > $values[4] &&
            $values[6] > $values[2] &&
            $values[6] > $values[4] &&
            $values[2] > $values[1] &&
            $values[2] > $values[3] &&
            $values[2] > $values[5] &&
            $values[4] > $values[1] &&
            $values[4] > $values[3] &&
            $values[4] > $values[5] &&
            $values[3] < $values[1] &&
            $values[3] < $values[5]) {
            return true;
        }
        return false;
    }

    public function isTopBottom($values)
    {
        $values = array_values($values);
        if ($values[0] < $values[2] &&
            $values[4] < $values[2] &&
            $values[2] < $values[1] &&
            $values[2] < $values[3]) {
            return true;
        }
        return false;
    }

    public function isTripleTop($values)
    {
        $values = array_values($values);
        if ($values[0] < $values[2] &&
            $values[0] < $values[4] &&
            $values[6] < $values[2] &&
            $values[6] < $values[4] &&
            $values[2] < $values[1] &&
            $values[2] < $values[3] &&
            $values[2] < $values[5] &&
            $values[4] < $values[1] &&
            $values[4] < $values[3] &&
            $values[4] < $values[5] ) {
            return true;
        }
        return false;
    }

    public function isHeadShoulders($values)
    {
        $values = array_values($values);
        if ($values[0] < $values[2] &&
            $values[0] < $values[4] &&
            $values[6] < $values[2] &&
            $values[6] < $values[4] &&
            $values[2] < $values[1] &&
            $values[2] < $values[3] &&
            $values[2] < $values[5] &&
            $values[4] < $values[1] &&
            $values[4] < $values[3] &&
            $values[4] < $values[5] &&
            $values[3] > $values[1] &&
            $values[3] > $values[5]) {
            return true;
        }
        return false;
    }

    public function isValuesLowTrend(array $values)
    {
        foreach ($values as $date => $value) {
            if ($this->trends[$date] <= $value) {
                return false;
            }
        }
        return true;
    }


    /**
     * @param float $left
     * @param float $right
     * @return bool
     */
    private function isGreater($left, $right) {
        return $left > $right;
    }

    /**
     * @param float $left
     * @param float $right
     * @return bool
     */
    private function isLess($left, $right) {
        return $left < $right;
    }





}