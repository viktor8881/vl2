<?php
namespace Course\View\Helper;

use Analysis\Entity\MoexPercentAnalysis;
use Zend\View\Helper\AbstractHelper;

class MaxOneDayTableStock extends AbstractHelper
{

    const MINIMUM_PERFORMANCE_VALUE = 10;

    public function __invoke(array $params)
    {
        $params = array_slice($params, 0, 20);
        usort($params, [$this, 'order']);
        return $this->view->tableStock($params);
    }


    private function order($a, $b)
    {
        $resultA = -100;
        /** @var $valueA MoexPercentAnalysis */
        foreach ($a['percent'] as $valueA) {
            if ($valueA->getPeriod() == 1 && $valueA->isQuotesGrowth()) {
                $resultA = $valueA->getDiffPercent();
                break;
            }
        }

        $resultB = -100;
        /** @var $valueB MoexPercentAnalysis */
        foreach ($b['percent'] as $valueB) {
            if ($valueB->getPeriod() == 1 && $valueB->isQuotesGrowth()) {
                $resultB = $valueB->getDiffPercent();
                break;
            }
        }

        if ($resultA == $resultB) {
            return 0;
        }
        return ($resultA < $resultB) ? 1 : -1;
    }

}
