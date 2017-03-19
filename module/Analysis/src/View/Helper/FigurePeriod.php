<?php

namespace Analysis\View\Helper;

use Analysis\Entity\FigureAnalysis;
use Zend\View\Helper\AbstractHelper;

class FigurePeriod extends AbstractHelper
{
    
    public function __invoke(FigureAnalysis $figure)
    {
        $dateStart = $figure->getFirstDate();
        $dateEnd = $figure->getLastDate();
        $interval = $dateStart->diff($dateEnd, true);
        return $dateStart->format('d.m.Y').' - '.$dateEnd->format('d.m.Y').' ('.$this->view->pluralDays((int)$interval->format('%a')).')';
    }
        
}
