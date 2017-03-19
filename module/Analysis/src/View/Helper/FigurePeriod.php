<?php

namespace Base\View\Helper;

use Analysis\Entity\FigureAnalysis;
use Zend\View\Helper\AbstractHelper;

class FigurePeriod extends AbstractHelper
{
    
    public function __invoke(FigureAnalysis $figure)  {
        $dateStart = $figure->getFirstDate();
        $dateEnd = $figure->getLastDate();
        return $dateStart->formatDMY().' - '.$dateEnd->formatDMY().' ('.$this->view->pluralDays($dateStart->diffDays($dateEnd)).')';
    }
        
}
