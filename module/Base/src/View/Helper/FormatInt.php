<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatInt extends AbstractHelper
{    

    public function __invoke($number)
    {
        return $this->view->formatNumber((int)$number, 0);
    }

}
