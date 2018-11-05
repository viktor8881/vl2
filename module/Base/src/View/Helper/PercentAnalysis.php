<?php
namespace Base\View\Helper;


use Analysis\Entity\MoexPercentAnalysis;
use Zend\View\Helper\AbstractHelper;

class PercentAnalysis extends AbstractHelper
{

    public function __invoke(MoexPercentAnalysis $taskPercentAnalysis)
    {
        $html = '';
            if ($taskPercentAnalysis->isQuotesGrowth()) {
                $sign = '+';
                $iconSign = '▲';
                $cssColor = 'color: rgb(5, 132, 11);';
            } else {
                $sign = '-';
                $iconSign = '▼';
                $cssColor = 'color: rgb(191, 0, 0);';
            }

            $html .= '<strong><span style="' . $cssColor . '"> '
                . $iconSign . ' за ' . $this->view->pluralDays($taskPercentAnalysis->getPeriod()) . ' '
                . $this->view->formatPercent($taskPercentAnalysis->getDiffPercent()) . '%'
                . '</span></strong><br />';
            $html .= $taskPercentAnalysis->getStartDateFormatDMY() . '<span style="margin-left:30px;">'
                . $this->view->formatMoney($taskPercentAnalysis->getStartValue()) . '</span><br />';
            $html .= $taskPercentAnalysis->getEndDateFormatDMY() . '<span style="margin-left:30px;">'
                . $this->view->formatMoney($taskPercentAnalysis->getEndValue()) . '</span><br />';
            $html .= '<span style="' . $cssColor . '; margin-left:88px;">' . $sign . ' '
                . $this->view->formatMoney($taskPercentAnalysis->getDiffMoneyValue()) .
                '</span><br />';
        return $html;
    }

}
