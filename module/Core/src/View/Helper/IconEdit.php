<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconEdit extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title)?'title="'._($this->view->escape($title)).'"':null;
        return '<span class="glyphicon glyphicon-edit" '._($title).'></span>';
    }
    
}

