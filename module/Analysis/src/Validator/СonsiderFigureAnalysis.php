<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 02.03.2019
 * Time: 23:57
 */

namespace Analysis\Validator;


use Analysis\Entity\MoexFigureAnalysis;
use Zend\Validator\AbstractValidator;

class СonsiderFigureAnalysis extends AbstractValidator
{

    const MORE_COUNT_NOT_CONSIDER = 20;
    const LESS_PERSEBT_NOT_CONSIDER = 1;

    /**
     * @param \Analysis\Entity\MoexFigureAnalysis $value
     * @return bool|void
     */
    public function isValid($value)
    {
        if ($value->isDoubleTop()) {
            return $this->isValidDoubleTop($value);
        } elseif ($value->isDoubleBottom()) {
            return $this->isValidDoubleBottom($value);
        } elseif ($value->isTreeTop() || $value->isHeadShoulders()) {
            return $this->isValidTreeTop($value);
        } elseif ($value->isTreeBottom() || $value->isRevertHeadShoulders()) {
            return $this->isValidTreeBottom($value);
        }
    }

    /**
     * @todo - возможно работает не правильно
     * @param MoexFigureAnalysis $figureAnalysis
     * @return bool
     */
    private function isValidTreeBottom(MoexFigureAnalysis $figureAnalysis)
    {
        $cacheCourses = $figureAnalysis->getCacheCourses();
        $max = max($cacheCourses[2]->getLastValue(), $cacheCourses[4]->getLastValue());

        $i = 0;
        foreach ($cacheCourses as $cacheCourse) {
            if (++$i === 1) {
                continue;
            }
            foreach ($cacheCourse->getDataValue() as $data) {
                if ($data['value'] <= $max && !isset($list[$data['data']])) {
                    $list[$data['data']] = $data['value'];
                }
            }
        }
        $countValues = count($list) + 2;
        if ($countValues > self::MORE_COUNT_NOT_CONSIDER) {
            return false;
        }

        $sum = $cacheCourses[1]->getLastValue() + $cacheCourses[3]->getLastValue() + $cacheCourses[5]->getLastValue();
        $minBottomValue = $sum / 3;
        $percent = abs(100 - ($max * 100 / $minBottomValue));
        if (self::LESS_PERSEBT_NOT_CONSIDER > $percent) {
            return false;
        }
        return true;
    }

    /**
     * @param MoexFigureAnalysis $figureAnalysis
     * @return bool
     */
    private function isValidTreeTop(MoexFigureAnalysis $figureAnalysis)
    {
        $cacheCourses = $figureAnalysis->getCacheCourses();
        $min = min($cacheCourses[2]->getLastValue(), $cacheCourses[4]->getLastValue());

        $i = 0;
        foreach ($cacheCourses as $cacheCourse) {
            if (++$i === 1) {
                continue;
            }
            foreach ($cacheCourse->getDataValue() as $data) {
                if ($data['value'] >= $min && !isset($list[$data['data']])) {
                    $list[$data['data']] = $data['value'];
                }
            }
        }
        $countValues = count($list) + 2;
        if ($countValues > self::MORE_COUNT_NOT_CONSIDER) {
            return false;
        }

        $sum = $cacheCourses[1]->getLastValue() + $cacheCourses[3]->getLastValue() + $cacheCourses[5]->getLastValue();
        $minTopValue = $sum / 3;
        $percent = abs(100 - ($minTopValue * 100 / $min));
        if (self::LESS_PERSEBT_NOT_CONSIDER > $percent) {
            return false;
        }
        return true;
    }


    /**
     * @param MoexFigureAnalysis $figureAnalysis
     * @return bool
     */
    private function isValidDoubleBottom(MoexFigureAnalysis $figureAnalysis)
    {
        $cacheCourses = $figureAnalysis->getCacheCourses();
        $max = $cacheCourses[2]->getLastValue();

        $i = 0;
        foreach ($cacheCourses as $cacheCourse) {
            if (++$i === 1) {
                continue;
            }
            foreach ($cacheCourse->getDataValue() as $data) {
                if ($data['value'] <= $max && !isset($list[$data['data']])) {
                    $list[$data['data']] = $data['value'];
                }
            }
        }
        $countValues = count($list) + 2;
        if ($countValues > self::MORE_COUNT_NOT_CONSIDER) {
            return false;
        }

        $sum = $cacheCourses[1]->getLastValue() + $cacheCourses[3]->getLastValue();
        $minBottomValue = $sum / 2;
        $percent = abs(100 - ($max * 100 / $minBottomValue));
        if (self::LESS_PERSEBT_NOT_CONSIDER > $percent) {
            return false;
        }
        return true;
    }



    /**
     * @param MoexFigureAnalysis $figureAnalysis
     * @return bool
     */
    private function isValidDoubleTop(MoexFigureAnalysis $figureAnalysis)
    {
        $cacheCourses = $figureAnalysis->getCacheCourses();
        $min = $cacheCourses[2]->getLastValue();

        $i = 0;
        foreach ($cacheCourses as $cacheCourse) {
            if (++$i === 1) {
                continue;
            }
            foreach ($cacheCourse->getDataValue() as $data) {
                if ($data['value'] >= $min && !isset($list[$data['data']])) {
                    $list[$data['data']] = $data['value'];
                }
            }
        }
        $countValues = count($list) + 2;
        if ($countValues > self::MORE_COUNT_NOT_CONSIDER) {
            return false;
        }

        $sum = $cacheCourses[1]->getLastValue() + $cacheCourses[3]->getLastValue();
        $minTopValue = $sum / 2;
        $percent = abs(100 - ($minTopValue * 100 / $min));
        if (self::LESS_PERSEBT_NOT_CONSIDER > $percent) {
            return false;
        }
        return true;
    }

}