<?php

namespace Analysis\Entity;

use Exchange\Entity\Exchange;
use Zend\Stdlib\ArrayObject;

class MoexPercentAnalysisCollection extends ArrayObject
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
     * @return MoexPercentAnalysis[]
     */
    public function listByExchange(Exchange $exchange)
    {
        $list = array();
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->isPercent() && $analysis->getExchange() == $exchange) {
                $list[] = $analysis;
            }
        }
        return $list;
    }

}