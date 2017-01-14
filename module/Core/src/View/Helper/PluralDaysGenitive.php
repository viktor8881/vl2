<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PluralDaysGenitive extends AbstractHelper
{

    private static $_ext = array('дня', 'дней', 'дней');

    public function __invoke($n, $viewCount = true)
    {
        return $this->view->plural($n, self::$_ext, $viewCount);
    }
}
