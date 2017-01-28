<?php


namespace Base\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class AccessPlugin extends AbstractPlugin
{

    // Этот метод проверяет, разрешено ли пользователю
    // посетить страницу
    public function checkAccess($actionName)
    {
        // ...
        return true;
    }
}