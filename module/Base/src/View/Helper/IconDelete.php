<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconDelete extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title) ? 'title="' . _($this->view->escapeHtml($title)) . '"' : '';
        return '<span class="glyphicon glyphicon-trash" ' . _($title) . '></span>';
    }

}
