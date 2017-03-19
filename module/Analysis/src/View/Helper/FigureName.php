<?php
namespace Analysis\View\Helper;

use Analysis\Entity\FigureAnalysis;
use Zend\View\Helper\AbstractHelper;

class FigureName extends AbstractHelper
{
    
    public function __invoke($figureId)  {
        $name = '';
        switch ($figureId) {
            case FigureAnalysis::FIGURE_DOUBLE_TOP:
                $name = _('двойная вершина');
                break;
            case FigureAnalysis::FIGURE_DOUBLE_BOTTOM:
                $name = _('двойное дно');
                break;
            case FigureAnalysis::FIGURE_TRIPLE_TOP:
                $name = _('тройная вершина');
                break;
            case FigureAnalysis::FIGURE_TRIPLE_BOTTOM:
                $name = _('тройное дно');
                break;
            case FigureAnalysis::FIGURE_HEADS_HOULDERS:
                $name = _('голова и плечи');
                break;
            case FigureAnalysis::FIGURE_RESERVE_HEADS_HOULDERS:
                $name = _('перевернутая голова и плечи');
                break;
            default:
                break;
        }
        return $name;
    }
        
}
