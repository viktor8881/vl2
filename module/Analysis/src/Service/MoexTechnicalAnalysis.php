<?php

namespace Analysis\Service;


use Base\Service\Math;

class MoexTechnicalAnalysis
{

    const PERSENT_UP_TREND = 5;
    const PERSENT_DOWN_TREND = 5;
    const PERSENT_EQUAL_TREND = 5;

    const SCALE_COMPARE_MONEY = 3;

    /**
     * Продолжающийся тренд.
     * Возрастающий, или «бычий», тренд (uptrend, upward, bullish trend)
     * характеризуется тем, что нижние колебания цены рынка повышаются.
     * Нижний критерий устанавливается на процент self::PERSENT_UP_TREND ниже предыдущего минимума.
     *
     * @param float[] $courses
     * @param float $percent
     *
     * @return boolean
     */
    public static function isUpTrend(array $courses, $percent = 5.0)
    {
        $courses = array_values($courses);
        $firstCourse = null;
        $i = 0;
        $iPercent = 0;
        foreach ($courses as $course) {
            if (++$i == 1) {
                $firstCourse = $course;
                continue;
            }
            $iPercent++;
            $lowCritery = $firstCourse * (1 + ($percent * $iPercent) / 100);
            if (self::compare($lowCritery, $course) == 1) {
                return false;
            }
        }
        return true;
    }


    /**
     * Продолжающийся тренд.
     * Убывающий, или «медвежий», тренд (downtrend, downward, bearish trend)
     * характеризуется тем, что максимальные цены колебаний рынка понижаются
     * Нижний критерий устанавливается на процент self::PERSENT_DOWN_TREND выше предыдущего минимума.
     *
     * @param float[] $courses
     * @param float  $percent
     *
     * @return boolean
     */
    public static function isDownTrend(array $courses, $percent = 5.0)
    {
        $courses = array_values($courses);
        $firstCourse = null;
        $i = 0;
        $iPercent = 0;
        foreach ($courses as $course) {
            if (++$i == 1) {
                $firstCourse = $course;
                continue;
            }
            $iPercent++;
            $hightCritery = $firstCourse * (1 - ($percent * $iPercent) / 100);
            if (self::compare($course, $hightCritery) == 1) {
                return false;
            }
//            $prevCourse = $hightCritery;
        }
        return true;
    }

    /**
     * Горизонтальный тренд, он показывает, что цены колеблются в
     * горизонтальном диапазоне (sideways, fl at market, trendless).
     * Нижний/верхний критерий устанавливается на процент self::PERSENT_EQUAL_TREND выше/ниже предыдущего минимума.
     *
     * @param float[] $courses
     * @param float $percent
     *
     * @return boolean
     */
    public static function isEqualChannel(array $courses, $percent = 5.0)
    {
        $courses = array_values($courses);
        $i = 0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i == 1) {
                $lowCritery = $course * (1 - ($percent / 100));
                $hightCritery = $course * (1 + ($percent / 100));
                continue;
            }
            if (self::compare($course, $hightCritery) == 1 || self::compare($lowCritery, $course) == 1) {
                return false;
            }
        }
        return true;
    }

    public static function isUpChannel(array $courses, $percent = 5)
    {
        $courses = array_values($courses);
        $i = 0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i == 1) {
                $hightCritery = $course * (1 + ($percent / 100));
                $lowCritery = $course * (1 - ($percent / 100));
                continue;
            }
            $hightCritery = $hightCritery * (1 + ($percent / 100));
            $lowCritery = $lowCritery * (1 + ($percent / 100));

            if (self::compare($course, $hightCritery) == 1 || self::compare($lowCritery, $course) == 1) {
                return false;
            }
        }
        return true;
    }

    public static function isDownChannel(array $courses, $percent = 5)
    {
        $courses = array_values($courses);
        $i = 0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i == 1) {
                $hightCritery = $course * (1 + ($percent / 100));
                $lowCritery = $course * (1 - ($percent / 100));
                continue;
            }
            $hightCritery = $hightCritery * (1 - ($percent / 100));
            $lowCritery = $lowCritery * (1 - ($percent / 100));

            if (self::compare($course, $hightCritery) == 1 or self::compare($lowCritery, $course) == 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * Двойная вершина double Top. Разворотная фигура
     * для повышательного тренда имеет вид буквы М. Двойная вершина является
     * сигналом более слабым, чем тройная вершина.
     *
     * @param float[] $courses
     * @param float $percent
     * @param float $percentDiffPeak
     *
     * @return boolean
     */
    public static function isDoubleTop(array $courses) {
        if (count($courses) != 5) {
            return false;
        }
        $courses = array_values($courses);
        if (self::compare($courses[1], $courses[2]) === 1
            && self::compare($courses[2], $courses[0]) === 1
            && self::compare($courses[2], $courses[4]) >= 0
            && self::compare($courses[3], $courses[2]) === 1
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param array $courses
     * @return bool
     */
    public static function isDoubleBottom(array $courses) {
        if (count($courses) != 5) {
            return false;
        }
        $courses = array_values($courses);
        if (self::compare($courses[0], $courses[2]) === 1
            && self::compare($courses[2], $courses[1]) === 1
            && self::compare($courses[2], $courses[3]) === 1
            && self::compare($courses[4], $courses[2]) >= 0
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param array $courses
     * @param int   $percent
     * @return bool
     */
    public static function isHeadShoulders(array $courses)
    {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);

        if (self::compare($courses[1], $courses[2]) === 1
            && self::compare($courses[1], $courses[4]) === 1

            && self::compare($courses[2], $courses[0]) === 1
            && self::compare($courses[2], $courses[6]) >= 0

            && self::compare($courses[3], $courses[1]) === 1
            && self::compare($courses[3], $courses[5]) === 1

            && self::compare($courses[4], $courses[0]) === 1
            && self::compare($courses[4], $courses[6]) >= 0

            && self::compare($courses[5], $courses[2]) === 1
            && self::compare($courses[5], $courses[4]) === 1
        ) {
            return true;
        }
        return false;
    }


    public static function isReverseHeadShoulders(array $courses)
    {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);

        if (self::compare($courses[0], $courses[2]) === 1
            && self::compare($courses[0], $courses[4]) === 1

            && self::compare($courses[1], $courses[3]) === 1

            && self::compare($courses[2], $courses[1]) === 1
            && self::compare($courses[2], $courses[5]) === 1

            && self::compare($courses[4], $courses[1]) === 1
            && self::compare($courses[4], $courses[5]) === 1

            && self::compare($courses[5], $courses[3]) === 1

            && self::compare($courses[6], $courses[2]) >= 0
            && self::compare($courses[6], $courses[4]) >= 0
        ) {

            return true;
        }
        return false;
    }


    public static function isTripleTop(array $courses)
    {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);

        if (self::compare($courses[1], $courses[2]) === 1
            && self::compare($courses[1], $courses[4]) === 1

            && self::compare($courses[2], $courses[0]) === 1
            && self::compare($courses[2], $courses[6]) >= 0

            && self::compare($courses[3], $courses[2]) === 1
            && self::compare($courses[3], $courses[4]) === 1

            && self::compare($courses[4], $courses[0]) === 1
            && self::compare($courses[4], $courses[6]) >= 0

            && self::compare($courses[5], $courses[2]) === 1
            && self::compare($courses[5], $courses[4]) === 1

        ) {
            return true;
        }
        return false;
    }


    public static function isTripleBottom(array $courses)
    {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);

        if (self::compare($courses[0], $courses[2]) === 1
            && self::compare($courses[0], $courses[4]) === 1

            && self::compare($courses[2], $courses[1]) === 1
            && self::compare($courses[2], $courses[3]) === 1
            && self::compare($courses[2], $courses[5]) === 1
            && self::compare($courses[2], $courses[6]) >= 0

            && self::compare($courses[4], $courses[1]) === 1
            && self::compare($courses[4], $courses[3]) === 1
            && self::compare($courses[4], $courses[5]) === 1
            && self::compare($courses[4], $courses[6]) >= 0

        ) {
            return true;
        }
        return false;
    }


    /**
     * «Восходящий» треугольник - верхняя граница треугольника образует
     * горизонтальную (или почти горизонтальную) линию сопротивления,
     * нижняя граница треугольника имеет восходящий наклон. Амплитуда колебаний внутри треугольника снижается.
     *
     * @param float[] $courses
     * @param float $percentHorizon
     *
     * @return boolean
     */
    public static function isAscendingTriangle(array $courses, $percentHorizon = 1.0) {
        if (count($courses) < 5) {
            return false;
        }
        $courses = array_values($courses);
        // определяем точку на горизонт. линии
        if (self::compare($courses[0], $courses[1]) == 1) {
            $startKey = 2;
        } elseif (self::compare($courses[1], $courses[0]) == 1) {
            $startKey = 1;
        } else {
            return false;
        }
        // определяем процент линии сопротивления
        if (self::compare($courses[$startKey + 1], $courses[$startKey + 3]) == 1) {
            return false;
        }
        $percentRes = ($courses[$startKey + 3] * 100 / $courses[$startKey + 1]) - 100;

        $i = 0;
        $listHorizon = $listRes = array();
        for ($index = $startKey; $index < count($courses); $index++) {
            if ($i++ & 1) {
                $listRes[] = $courses[$index];
            } else {
                $listHorizon[] = $courses[$index];
            }
        }

        if (!(self::isEqualChannel($listHorizon, $percentHorizon) && self::isUpChannel($listRes, $percentRes))) {
            return false;
        }
        return true;
    }


    /**
     * Hизходящий» треугольник - нижняя граница треугольника образует горизонтальную
     * (или почти горизонтальную) линию поддержки, верхняя граница треугольника
     * имеет нисходящий наклон. Амплитуда колебаний внутри треугольника снижается.
     *
     * @param float[] $courses
     * @param float $percentHorizon
     *
     * @return boolean
     */
    public static function isDescendingTriangle(array $courses, $percentHorizon = 1.0) {
        if (count($courses) < 5) {
            return false;
        }
        $courses = array_values($courses);

        // определяем точку на горизонт. линии
        if (self::compare($courses[0], $courses[1]) == 1) {
            $startKey = 1;
        } elseif (self::compare($courses[1], $courses[0]) == 1) {
            $startKey = 2;
        } else {
            return false;
        }
        // определяем процент линии сопротивления
        if (self::compare($courses[$startKey + 3], $courses[$startKey + 1])
            == 1
        ) {
            return false;
        }
        $percentSup = ($courses[$startKey + 1] * 100 / $courses[$startKey + 3])
            - 100;

        $i = 0;
        $listHorizon = $listSup = array();
        for ($index = $startKey; $index < count($courses); $index++) {
            if ($i++ & 1) {
                $listSup[] = $courses[$index];
            } else {
                $listHorizon[] = $courses[$index];
            }
        }

        if (!(self::isEqualChannel($listHorizon, $percentHorizon)
            && self::isDownChannel($listSup, $percentSup))
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param float $value1
     * @param float $value2
     *
     * @return boolean
     */
    private static function compare($value1, $value2)
    {
        return Math::compare($value1, $value2, self::SCALE_COMPARE_MONEY);
    }

}