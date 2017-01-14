<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ItemsNotFound extends AbstractHelper
{

    public function __invoke($title, $class = "well")
    {
        return '<p class="'.$class.'">'.$this->view->escapeHtml($title).'</p>';
    }

}