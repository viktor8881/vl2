<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;


class PageHeader extends AbstractHelper
{

    private $lastTitle;

    public function setTitle($title)
    {
        $this->lastTitle = $title;
        $this->view->headTitle($title);
        return $this;
    }

    public function render()
    {
        return (!is_null($this->lastTitle)) ? '<h1 class="page-header" >' .
            $this->view->escapeHtml($this->lastTitle)
            . '</h1>' : '';
    }


}
