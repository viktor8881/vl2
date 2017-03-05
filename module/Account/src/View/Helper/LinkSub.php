<?php

namespace Account\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LinkSub extends AbstractHelper
{

    public function __invoke($name)
    {
        return '<a href="' . $this->view->url('account', ['action' => 'sub']) . '">' . $this->view->iconSub($name) . ' ' . _($this->view->escapeHtml($name)) . '</a>';
    }

}