<?php

namespace Analysis\Service;


use Base\Service\Math;

class TechnicalAnalysis
{

    const PERSENT_UP_TREND = 5;
    const PERSENT_DOWN_TREND = 5;
    const PERSENT_EQUAL_TREND = 5;
    // количество подряд для понимания уверенного тренда.
    const SURE_TREND = 5;

    const SCALE_COMPARE_MONEY = 3;

    /**
     * Продолжающийся тренд.
     * Возрастающий, или «бычий», тренд (uptrend, upward, bullish trend)
     * характеризуется тем, что нижние колебания цены рынка повышаются.
     * Нижний критерий устанавливается на процент self::PERSENT_UP_TREND ниже предыдущего минимума.
     *
     * @param array $courses
     * @param float $persent - self::PERSENT_UP_TREND
     *
     * @return boolean
     */
    public static function isUpTrend(array $courses, $percent = 5)
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
     * @param array $courses
     * @param type  $percent
     *
     * @return boolean
     */
    public static function isDownTrend(array $courses, $percent = 5)
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
     * @param array $courses
     * @param float $percent
     *
     * @return boolean
     */
    public static function isEqualChannel(array $courses, $percent = 5)
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
            if (self::compare($course, $hightCritery) == 1 or self::compare(
                    $lowCritery, $course
                ) == 1
            ) {
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

            if (self::compare($course, $hightCritery) == 1 or self::compare(
                    $lowCritery, $course
                ) == 1
            ) {
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
            if (self::compare($course, $hightCritery) == 1 or self::compare(
                    $lowCritery, $course
                ) == 1
            ) {
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
     * @param array $courses
     * @param float $percent
     * @param float $percentDiffPeak разница в процентах между верхом и низом волны
     *
     * @return boolean
     */
    public static function isDoubleTop(array $courses, $percent = 5,
        $percentDiffPeak = 20
    ) {
        if (count($courses) != 5) {
            return false;
        }
        $courses = array_values($courses);
        if (self::compare($courses[1], $courses[0]) == 1
            && self::compare($courses[1], $courses[2]) == 1
            && self::compare($courses[2], $courses[4]) >= 0
            && self::compare($courses[3], $courses[2]) == 1
            && self::compare($courses[3], $courses[4]) == 1
        ) {

            $hight1 = $courses[1] * (1 + ($percent / 100));
            $low1 = $courses[1] * (1 - ($percent / 100));
            $hight2 = $courses[2] * (1 + ($percentDiffPeak / 100));
            $low2 = $courses[2] * (1 - ($percentDiffPeak / 100));

//            pr($courses);
//            echo('$hight1 = '.$hight1."\n");
//            echo('$low1 = '.$low1."\n");
//            echo('$hight2 = '.$hight2."\n");
//            echo('$low2 = '.$low2."\n");
//            var_dump(self::compare($courses[1], $hight2));
//            var_dump(self::compare($hight1, $courses[3]));
//            var_dump(self::compare($courses[3], $low1));
//            var_dump(self::compare($low2, $courses[0]));


            if (self::compare($courses[1], $hight2) == 1
                && self::compare($hight1, $courses[3]) >= 0
                && self::compare($courses[3], $low1) >= 0
                && self::compare($low2, $courses[0]) >= 0
            ) {

                return true;
            }
        }
        return false;
    }

    public static function isDoubleBottom(array $courses, $percent = 5,
        $percentDiffPeak = 20
    ) {
        if (count($courses) != 5) {
            return false;
        }
        $courses = array_values($courses);
        if (self::compare($courses[0], $courses[1]) == 1
            && self::compare($courses[2], $courses[1]) == 1
            && self::compare($courses[2], $courses[3]) == 1
            && self::compare($courses[4], $courses[3]) == 1
            && self::compare($courses[4], $courses[2]) >= 0
        ) {

            $diffPeak = $courses[2] * (1 - ($percentDiffPeak / 100));
            $hight1 = $courses[1] * (1 + ($percent / 100));
            $low1 = $courses[1] * (1 - ($percent / 100));
            $low2 = $courses[2] * (1 - ($percent / 100));
            if (self::compare($diffPeak, $courses[1]) == 1
                && self::compare($hight1, $courses[3]) >= 0
                && self::compare($courses[3], $low1) >= 0
                && self::compare($courses[0], $low2) >= 0
            ) {

                return true;
            }
        }
        return false;
    }

    public static function isHeadShoulders(array $courses, $percent = 5)
    {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);
        $hight2 = $courses[2] * (1 + ($percent / 100));
        $low2 = $courses[2] * (1 - ($percent / 100));

        if (self::compare($courses[1], $courses[0]) == 1
            && self::compare($courses[2], $courses[0]) == 1
            && self::compare($courses[3], $courses[0]) == 1
            && self::compare($courses[4], $courses[0]) == 1
            && self::compare($courses[5], $courses[0]) == 1
            && self::compare($courses[6], $courses[0]) == 1

            && self::compare($courses[1], $courses[2]) == 1
            && self::compare($courses[3], $courses[1]) == 1
            && self::compare($courses[1], $courses[4]) == 1
            && self::compare($courses[5], $courses[1]) == 1
            && self::compare($courses[1], $courses[6]) == 1

            && self::compare($courses[3], $courses[2]) == 1
            && self::compare($hight2, $courses[4]) == 1
            && self::compare($courses[4], $low2) == 1
            && self::compare($courses[5], $courses[2]) == 1
            && self::compare($courses[2], $courses[6]) == 1

            && self::compare($courses[3], $courses[4]) == 1
            && self::compare($courses[3], $courses[5]) == 1
            && self::compare($courses[3], $courses[6]) == 1

            && self::compare($courses[5], $courses[4]) == 1
            && self::compare($courses[4], $courses[6]) == 1

            && self::compare($courses[5], $courses[6]) == 1
        ) {
            return true;
        }
        return false;
    }


    public static function isReverseHeadShoulders(array $courses, $percent = 5)
    {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);
        $hight2 = $courses[2] * (1 + ($percent / 100));
        $low2 = $courses[2] * (1 - ($percent / 100));

        if (self::compare($courses[0], $courses[1]) == 1
            && self::compare($courses[0], $courses[2]) == 1
            && self::compare($courses[0], $courses[3]) == 1
            && self::compare($courses[0], $courses[4]) == 1
            && self::compare($courses[0], $courses[5]) == 1
            && self::compare($courses[0], $courses[6]) == 1

            && self::compare($courses[2], $courses[1]) == 1
            && self::compare($courses[1], $courses[3]) == 1
            && self::compare($courses[4], $courses[1]) == 1
            && self::compare($courses[1], $courses[5]) == 1
            && self::compare($courses[6], $courses[1]) == 1

            && self::compare($courses[2], $courses[3]) == 1
            && self::compare($courses[2], $courses[5]) == 1
            && self::compare($hight2, $courses[4]) == 1
            && self::compare($courses[4], $low2) == 1
            && self::compare($courses[6], $courses[2]) == 1

            && self::compare($courses[4], $courses[3]) == 1
            && self::compare($courses[5], $courses[3]) == 1
            && self::compare($courses[6], $courses[3]) == 1

            && self::compare($courses[4], $courses[5]) == 1
            && self::compare($courses[6], $courses[4]) == 1

            && self::compare($courses[6], $courses[5]) == 1
        ) {

            return true;
        }
        return false;
    }


    public static function isTripleTop(array $courses, $percentBottom = 5,
        $percentTop = 10
    ) {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);

        $hightTop = $courses[1] * (1 + ($percentTop / 100));
        $lowTop = $courses[1] * (1 - ($percentTop / 100));

        $hightBottom = $courses[2] * (1 + ($percentBottom / 100));
        $lowBottom = $courses[2] * (1 - ($percentBottom / 100));

        if (self::compare($courses[1], $courses[0]) == 1
            && self::compare($courses[2], $courses[0]) == 1
            && self::compare($courses[3], $courses[0]) == 1
            && self::compare($courses[4], $courses[0]) == 1
            && self::compare($courses[5], $courses[0]) == 1
            && self::compare($courses[6], $courses[0]) == 1

            && self::compare($courses[1], $courses[2]) == 1
            && self::compare($hightTop, $courses[3]) == 1
            && self::compare($courses[3], $lowTop) == 1
            && self::compare($courses[1], $courses[4]) == 1
            && self::compare($hightTop, $courses[5]) == 1
            && self::compare($courses[5], $lowTop) == 1
            && self::compare($courses[1], $courses[6]) == 1

            && self::compare($courses[3], $courses[2]) == 1
            && self::compare($hightBottom, $courses[4]) == 1
            && self::compare($courses[4], $lowBottom) == 1
            && self::compare($courses[5], $courses[2]) == 1
            && self::compare($courses[2], $courses[6]) == 1

            && self::compare($courses[3], $courses[4]) == 1
            && self::compare($hightTop, $courses[5]) == 1
            && self::compare($courses[5], $lowTop) == 1
            && self::compare($courses[3], $courses[6]) == 1

            && self::compare($courses[5], $courses[4]) == 1
            && self::compare($courses[4], $courses[6]) == 1

            && self::compare($courses[5], $courses[6]) == 1
        ) {
            return true;
        }
        return false;
    }


    public static function isTripleBottom(array $courses, $percentBottom = 5,
        $percentTop = 10
    ) {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);

        $hightBottom = $courses[1] * (1 + ($percentBottom / 100));
        $lowBottom = $courses[1] * (1 - ($percentBottom / 100));

        $hightTop = $courses[2] * (1 + ($percentTop / 100));
        $lowTop = $courses[2] * (1 - ($percentTop / 100));

        if (self::compare($courses[0], $courses[1]) == 1
            && self::compare($courses[0], $courses[2]) == 1
            && self::compare($courses[0], $courses[3]) == 1
            && self::compare($courses[0], $courses[4]) == 1
            && self::compare($courses[0], $courses[5]) == 1
            && self::compare($courses[0], $courses[6]) == 1

            && self::compare($courses[2], $courses[1]) == 1
            && self::compare($hightBottom, $courses[3]) == 1
            && self::compare($courses[3], $lowBottom) == 1
            && self::compare($courses[4], $courses[1]) == 1
            && self::compare($hightBottom, $courses[5]) == 1
            && self::compare($courses[5], $lowBottom) == 1
            && self::compare($courses[6], $courses[1]) == 1

            && self::compare($courses[2], $courses[3]) == 1
            && self::compare($hightTop, $courses[4]) == 1
            && self::compare($courses[4], $lowTop) == 1
            && self::compare($courses[2], $courses[5]) == 1
            && self::compare($courses[6], $courses[2]) == 1

            && self::compare($courses[4], $courses[3]) == 1
            && self::compare($hightBottom, $courses[5]) == 1
            && self::compare($courses[5], $lowBottom) == 1
            && self::compare($courses[6], $courses[3]) == 1

            && self::compare($courses[4], $courses[5]) == 1
            && self::compare($courses[6], $courses[4]) == 1

            && self::compare($courses[6], $courses[5]) == 1
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
     * @param array $courses
     *
     * @return boolean
     */
    public static function isAscendingTriangle(array $courses,
        $percentHorizon = 1
    ) {
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
        if (self::compare($courses[$startKey + 1], $courses[$startKey + 3])
            == 1
        ) {
            return false;
        }
        $percentRes = ($courses[$startKey + 3] * 100 / $courses[$startKey + 1])
            - 100;

        $i = 0;
        $listHorizon = $listRes = array();
        for ($index = $startKey; $index < count($courses); $index++) {
            if ($i++ & 1) {
                $listRes[] = $courses[$index];
            } else {
                $listHorizon[] = $courses[$index];
            }
        }

        if (!(self::isEqualChannel($listHorizon, $percentHorizon)
            && self::isUpChannel($listRes, $percentRes))
        ) {
            return false;
        }
        return true;
    }


    /**
     * Hизходящий» треугольник - нижняя граница треугольника образует горизонтальную
     * (или почти горизонтальную) линию поддержки, верхняя граница треугольника
     * имеет нисходящий наклон. Амплитуда колебаний внутри треугольника снижается.
     *
     * @param array $courses
     * @param type  $percentHorizon
     *
     * @return boolean
     */
    public static function isDescendingTriangle(array $courses,
        $percentHorizon = 1
    ) {
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
     * @param fload $value1
     * @param fload $value2
     *
     * @return boolean
     */
    private static function compare($value1, $value2)
    {
        return Math::compare($value1, $value2, self::SCALE_COMPARE_MONEY);
    }

}