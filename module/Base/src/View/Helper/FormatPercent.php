<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatPercent extends AbstractHelper
{

    public function __invoke($sum, $itemName = false)
    {
//        $formatSum = $this->view->formatNumber((float)$sum, Base_Container::getManager('setting')->getRoundPercent());
        $formatSum = $this->view->formatNumber((float)$sum, 2);
        if ($itemName) {
            $formatSum .= '%';
        }
        return $formatSum;
    }


}
