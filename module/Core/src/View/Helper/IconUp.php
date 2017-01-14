<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconUp extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title)?'title="'._($this->view->escape($title)).'"':null;
        return '<span title="'._($title).'">▲</span>';
    }
    
}
