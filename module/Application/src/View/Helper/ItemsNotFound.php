<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 11.12.2016
 * Time: 6:47
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;


class ItemsNotFound extends AbstractHelper
{

    public function __invoke($title, $class = "well")
    {
        return '<p class="'.$class.'">'.$this->view->escapeHtml($title).'</p>';
    }

}