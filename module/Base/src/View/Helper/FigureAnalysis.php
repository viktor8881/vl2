<?php
namespace Base\View\Helper;


use Zend\View\Helper\AbstractHelper;
use Analysis\Entity\MoexFigureAnalysis;

class FigureAnalysis extends AbstractHelper
{

    public function __invoke(MoexFigureAnalysis $taskFigureAnalyzes)
    {
        $html = '';
        $html .= '<p class="text-strong">'._("Найдена фигура").' '.$this->view->figureName($taskFigureAnalyzes->getFigure()).'</p>';
        $html .= '<p>'._('Период образования').' - '.$this->view->figurePeriod($taskFigureAnalyzes).'</p>';
        $html .= '<p>'._('Процент').' - '.$this->view->formatPercent($taskFigureAnalyzes->getPercentCacheCourses()).'</p>';
        return $html;
    }

}
