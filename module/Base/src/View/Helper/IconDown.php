<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconDown extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title) ? 'title="' . _($this->view->escapeHtml($title)) . '"'
            : null;
        return '<span title="' . _($title) . '">▼</span>';
    }

}
