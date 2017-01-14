<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatNumber extends AbstractHelper
{

    public function __invoke($number, $numFraction=2 )
    {        
        return '<span class="nowrap">'.number_format((float)$number, (int)$numFraction, '.', ' ').'</span>';
    }
    
    
}
