<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconDelete extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title) ? 'title="' . _($this->view->escape($title)) . '"'
            : null;
        return '<span class="glyphicon glyphicon-trash" ' . _($title)
            . '></span>';
    }

}
