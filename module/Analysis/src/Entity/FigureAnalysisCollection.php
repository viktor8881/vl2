<?php

namespace Analysis\Entity;

use Exchange\Entity\Exchange;
use Zend\Stdlib\ArrayObject;

class FigureAnalysisCollection extends ArrayObject
{

    /**
     * @return Exchange[]
     */
    public function listExchange()
    {
        $result = [];
        /** @var $analysis FigureAnalysis */
        foreach ($this->getIterator() as $analysis) {
            $result[] = $analysis->getExchange();
        }
        return $result;
    }

    /**
     * @param Exchange $exchange
     * @return FigureAnalysis[]
     */
    public function listByExchange(Exchange $exchange)
    {
        $list = array();
        /** @var $analysis FigureAnalysis */
        foreach ($this->getIterator() as $analysis) {
            if ($analysis->getExchange() == $exchange) {
                $list[] = $analysis;
            }
        }
        return $list;
    }

}