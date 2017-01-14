<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatMetal extends AbstractHelper
{

    public function __invoke($sum) {
//        return $this->view->formatNumber((float)$sum, Core_Container::getManager('setting')->getRoundMetal());
        return $this->view->formatNumber((float)$sum, 2);
    }
    
    
}
