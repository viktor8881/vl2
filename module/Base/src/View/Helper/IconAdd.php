<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconAdd extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title) ? 'title="' . _($this->view->escapeHtml($title)) . '"' : null;
        return '<span class="glyphicon glyphicon-plus" ' . _($title) . '></span>';
    }

}