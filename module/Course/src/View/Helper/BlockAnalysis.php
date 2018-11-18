<?php
namespace Course\View\Helper;


use Analysis\Entity\MoexFigureAnalysis;
use Zend\View\Helper\AbstractHelper;

class BlockAnalysis extends AbstractHelper
{

    /**
     * @param array $analysis
     * @return string
     */
    public function __invoke(array $analysis)
    {
        $html = '';
        $countBlock = $this->countBlocks($analysis);
        if ($countBlock) {
            $html .= '<div class="row">';
            $figures = $this->fetchAllMinFigures($analysis['figure']);
            if ($figures) {
                $html .= '<div class="col-sm-'.(12/$countBlock).'">';
                foreach ($figures as $figure) {
                    $html .= $this->view->figureAnalysis($figure);
                }
                $html .= '</div>';
            }
            if (count($analysis['overtime'])) {
                foreach ($analysis['overtime'] as $overtime) {
                    $html .= '<div class="col-sm-'.(12/$countBlock).'">';
                    $html .= $this->view->overtimeAnalysis($overtime);
                    $html .= '</div>';
                }
            }
            if (count($analysis['percent'])) {
                foreach ($analysis['percent'] as $percent) {
                    $html .= '<div class="col-sm-'.(12/$countBlock).'">';
                    $html .= $this->view->percentAnalysis($percent);
                    $html .= '</div>';
                }
            }
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * @param array $figures
     * @return MoexFigureAnalysis|null
     */
    private function fetchAllMinFigures(array $figures)
    {
        $result = [];
        /** @var $f MoexFigureAnalysis */
        foreach ($figures as $f) {
            $key = $f->getFigure();
            if (!isset($result[$key])) {
                $result[$key] = $f;
                continue;
            }
            if ($f->getPercentCacheCourses() < $result[$key]->getPercentCacheCourses()) {
                $result[$key] = $f;
            }
        }
        return $result;
    }

    /**
     * @param array $analysis
     * @return int
     */
    private function countBlocks(array $analysis)
    {
        $count = 0;
        if (count($analysis['overtime'])) {
            $count++;
        }
        if (count($analysis['percent'])) {
            $count++;
        }
        if (count($analysis['figure'])) {
            $count++;
        }
        return $count;
    }

}
