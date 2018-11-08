<?php
namespace Base\View\Helper;


use Course\Entity\MoexCacheCourse;
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
        $html .=  '<ul>';
        /** @var $course MoexCacheCourse */
        foreach ($taskFigureAnalyzes->getCacheCourses() as $course) {
            $html .=  '<li><span class="small">' . $course->getFirstDateFormatDMY(). '</span> ' . $this->view->formatMoney($course->getFirstValue()) . '</li>';
        }
        $html .=  '</ul>';
        return $html;
    }

}
