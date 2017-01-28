<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatMoney extends AbstractHelper
{

    public function __invoke($sum, $itemName = false)
    {
//        $formatSum = $this->view->formatNumber((float)$sum, Base_Container::getManager('setting')->getRoundMoney());
        $formatSum = $this->view->formatNumber((float)$sum, 2);
        if ($itemName) {
//            $formatSum.=' '.Base_Container::getManager('setting')->getMoneyUnit();
            $formatSum .= ' руб.';
        }
        return $formatSum;
    }


}
