<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class IconFavorite extends AbstractHelper
{

    public function __invoke($title = null)
    {
        $title = ($title) ? 'title="' . _($this->view->escapeHtml($title)) . '"' : '';
        return '<span class="glyphicon glyphicon-star-empty" ' . _($title) . '></span>';
    }

}
