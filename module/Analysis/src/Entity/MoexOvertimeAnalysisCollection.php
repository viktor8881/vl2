<?php

namespace Analysis\Entity;

use Exchange\Entity\Exchange;
use Zend\Stdlib\ArrayObject;

class MoexOvertimeAnalysisCollection extends ArrayObject
{

    /**
     * @return array
     */
    public function listExchange()
    {
        $result = [];
        /** @var $analysis TaskPercentAnalysis */
        foreach ($this->getIterator() as $analysis) {
            $result[] = $analysis->getExchange();
        }
        return $result;
    }


    /**
     * @param Exchange $exchange
     * @return MoexOvertimeAnalysis|null
     */
    public function getByExchange(Exchange $exchange)
    {
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isOvertime() && $analysis->getExchange() == $exchange ) {
                return $analysis;
            }
        }
        return null;
    }

}