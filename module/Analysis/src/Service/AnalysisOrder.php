<?php

namespace Analysis\Service;


use Analysis\Entity\MoexFigureAnalysis;

class AnalysisOrder
{

    public static function order(array $a, array $b)
    {
        $maskFigureA = self::findFigure($a['figure']);
        $maskFigureB = self::findFigure($b['figure']);
        if ($maskFigureA == $maskFigureB) {
            return 0;
        }
        return ($maskFigureA < $maskFigureB) ? 1 : -1;
    }

    /**
     * @param array $figures
     * @return array
     */
    public static function findFigure(array $figures)
    {
        $revertHeadSholders = 0;
        $tripleBottom = 0;
        $doubleBottom = 0;
        foreach ($figures as $entity) {
            switch ($entity->getFigure()) {
                case MoexFigureAnalysis::FIGURE_DOUBLE_BOTTOM :
                    $doubleBottom = 1;
                    break;
                case MoexFigureAnalysis::FIGURE_TRIPLE_BOTTOM :
                    $tripleBottom = 1;
                    break;
                case MoexFigureAnalysis::FIGURE_RESERVE_HEADS_HOULDERS :
                    $revertHeadSholders = 1;
                    break;
            }
        }
        return $revertHeadSholders.$tripleBottom.$doubleBottom;
    }

}