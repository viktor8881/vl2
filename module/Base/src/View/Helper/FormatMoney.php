<?php
namespace Base\View\Helper;


use Exchange\Entity\Exchange;
use Zend\View\Helper\AbstractHelper;

class FormatMoney extends AbstractHelper
{

    public function __invoke($sum, Exchange $exchange = null)
    {
        $formatSum = $this->view->formatNumber((float)$sum, 2);
        if (!is_null($exchange)) {
            $formatSum .= ' ' . $this->view->escapeHtml($exchange->getShortName());
        }
        return $formatSum;
    }


}
