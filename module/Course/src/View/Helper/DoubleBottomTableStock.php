<?php
namespace Course\View\Helper;

use Analysis\Entity\MoexFigureAnalysis;
use Analysis\Entity\MoexPercentAnalysis;
use Course\Entity\MoexCacheCourse;
use Zend\View\Helper\AbstractHelper;

class DoubleBottomTableStock extends AbstractHelper
{

    const MINIMUM_PERFORMANCE_VALUE = 30;

    public function __invoke(array $params)
    {
        usort($params, [$this, 'order']);
        return $this->view->tableStock($params);
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    private function order($a, $b)
    {
        $resultA = $this->getWaveMaxValue($a['figure']);

        $resultB = $this->getWaveMaxValue($b['figure']);;

        if ($resultA == $resultB) {
            return 0;
        }
        return ($resultA < $resultB) ? 1 : -1;
    }

    /**
     * @param array $figures
     * @return float
     */
    private function getWaveMaxValue(array $figures): float
    {
        /** @var $figure MoexFigureAnalysis */
        $maxValue = 0;
        foreach ($figures as $figure) {
            if (!$figure->isDoubleBottom()) {
                continue;
            }
            $countValues = $figure->countDataValues() - $figure->countFirstDataValue() - 3;
            if ($countValues > self::MINIMUM_PERFORMANCE_VALUE) {
                continue;
            }

            $cacheCourses = $figure->getCacheCourses();
            $minTop = $cacheCourses[2]->getLastValue();
            $maxBottom = max($cacheCourses[1]->getLastValue(), $cacheCourses[3]->getLastValue());

            $waveСoefficient = ($minTop / $maxBottom) + 1;
            if ($maxValue < $waveСoefficient) {
                $maxValue = $waveСoefficient;
            }
        }
        return $maxValue;
    }

}
