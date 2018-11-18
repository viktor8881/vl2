<?php

namespace Analysis\Service;


use Analysis\Entity\MoexFigureAnalysis;

class AnalysisOrder
{

//    public static function order(array $a, array $b)
//    {
//        $maskFigureA = self::findFigure($a['figure']);
//        $maskFigureB = self::findFigure($b['figure']);
//        if ($maskFigureA == $maskFigureB) {
//            return 0;
//        }
//        return ($maskFigureA < $maskFigureB) ? 1 : -1;
//    }

    /**
     * @param array $figures
     * @return array
     */
    public static function findFigure(array $figures)
    {
        $revertHeadSholders = null;
        $tripleBottom = null;
        $doubleBottom = null;
        foreach ($figures as $entity) {
            switch ($entity->getFigure()) {
                case MoexFigureAnalysis::FIGURE_DOUBLE_BOTTOM :
                    if (!$doubleBottom || ($entity->getFirstDate() > $doubleBottom->getFirstDate())) {
                        $doubleBottom = $entity;
                    }
                    break;
                case MoexFigureAnalysis::FIGURE_TRIPLE_BOTTOM :
                    if (!$tripleBottom || ($entity->getFirstDate() > $tripleBottom->getFirstDate())) {
                        $tripleBottom = $entity;
                    }
                    break;
                case MoexFigureAnalysis::FIGURE_RESERVE_HEADS_HOULDERS :
                    if (!$revertHeadSholders || ($entity->getFirstDate() > $revertHeadSholders->getFirstDate())) {
                        $revertHeadSholders = $entity;
                    }
                    break;
            }
        }
        return [$revertHeadSholders, $tripleBottom, $doubleBottom];
    }

}