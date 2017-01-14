<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatMoney extends AbstractHelper
{

    public function __invoke($sum, $itemName=false)
    {
//        $formatSum = $this->view->formatNumber((float)$sum, Core_Container::getManager('setting')->getRoundMoney());
        $formatSum = $this->view->formatNumber((float)$sum, 2);
        if ($itemName) {
//            $formatSum.=' '.Core_Container::getManager('setting')->getMoneyUnit();
            $formatSum.=' руб.';
        }
        return $formatSum;
    }
    
    
}
