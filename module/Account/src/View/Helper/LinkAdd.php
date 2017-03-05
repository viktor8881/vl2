<?php

namespace Account\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LinkAdd extends AbstractHelper
{

    public function __invoke($name)
    {
        return '<a href="' . $this->view->url('account', ['action' => 'add']) . '">' . $this->view->iconAdd($name) . ' ' . _($this->view->escapeHtml($name)) . '</a>';
    }

}