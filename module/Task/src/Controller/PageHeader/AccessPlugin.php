<?php
namespace Core\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class PageHeader extends AbstractPlugin
{

    public function checkAccess($actionName)
    {
        // ...
        return true;
    }
}