<?php
namespace Course\View\Helper;


use Analysis\Entity\MoexFigureAnalysis;
use Zend\View\Helper\AbstractHelper;

class FigureAnalysis extends AbstractHelper
{

    public function __invoke(MoexFigureAnalysis $taskFigureAnalyzes)
    {
        $html = '';
        $html .= '<p class="text-strong">'._("Найдена фигура").' '.$this->view->figureName($taskFigureAnalyzes->getFigure()).'</p>';
        $html .= '<p>'._('Период образования').' - '.$this->view->figurePeriod($taskFigureAnalyzes).'</p>';
        $html .= '<p>'._('Процент').' - '.$this->view->formatPercent($taskFigureAnalyzes->getPercentCacheCourses()).'</p>';
//        $html .=  '<ul>';
//        /** @var $course MoexCacheCourse */
//        foreach ($taskFigureAnalyzes->getCacheCourses() as $course) {
//            $html .=  '<li><span class="small">' . $course->getLastDateFormatDMY(). '</span> ' . $this->view->formatNumber($course->getLastValue(), 3) . '</li>';
//        }
//        $html .=  '</ul>';
        return $html;
    }

}
