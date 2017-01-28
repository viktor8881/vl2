<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PluralDays extends AbstractHelper
{
    private static $_ext = array('день', 'дня', 'дней');

    public function __invoke($n, $viewCount = true)
    {
        return $this->view->plural($n, self::$_ext, $viewCount);
    }
}
