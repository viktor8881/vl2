<?php

namespace Analysis\Entity;

use Exchange\Entity\Exchange;
use Zend\Stdlib\ArrayObject;

class MoexFigureAnalysisCollection extends ArrayObject
{

    /**
     * @return Exchange[]
     */
    public function listExchange()
    {
        $result = [];
        /** @var $analysis MoexFigureAnalysis */
        foreach ($this->getIterator() as $analysis) {
            $result[] = $analysis->getExchange();
        }
        return $result;
    }

    /**
     * @param Exchange $exchange
     * @return MoexFigureAnalysis[]
     */
    public function listByExchange(Exchange $exchange)
    {
        $list = array();
        /** @var $analysis MoexFigureAnalysis */
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->getExchange() == $exchange) {
                $list[] = $analysis;
            }
        }
        return $list;
    }

}