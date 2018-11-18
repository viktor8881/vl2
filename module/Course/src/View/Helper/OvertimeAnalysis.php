<?php
namespace Course\View\Helper;


use Analysis\Entity\MoexOvertimeAnalysis;
use Zend\View\Helper\AbstractHelper;

class OvertimeAnalysis extends AbstractHelper
{

    public function __invoke(MoexOvertimeAnalysis $taskOvertimeAnalysis)
    {
        $html = '';
        if ($taskOvertimeAnalysis->isQuotesGrowth()) {
            $html .=  '<p>Повышение курса в течении '
                . $this->view->pluralDaysGenitive($taskOvertimeAnalysis->countData(), true)
                . ' <span style="color: rgb(5, 132, 11);"> ▲ ' . $this->view->formatPercent($taskOvertimeAnalysis->getDiffPercent()) . '%</span>'
                . '</p>';
        } else {
            $html .=  '<p>Понижение курса в течении '
                . $this->view->pluralDaysGenitive($taskOvertimeAnalysis->countData(), true)
                . ' <span style="color: rgb(191, 0, 0);"> ▼ ' . $this->view->formatPercent($taskOvertimeAnalysis->getDiffPercent()) . '%</span>'
                . '</p>';
        }

        $html .=  '<ul>';
        foreach ($taskOvertimeAnalysis->getCourses() as $course) {
            $html .=  '<li><span class="small">' . $course->getDateFormatDMY(). '</span> ' . $this->view->formatMoney($course->getValue()) . '</li>';
        }
        $html .=  '</ul>';
        return $html;
    }

}
