<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 11.12.2016
 * Time: 4:40
 */

namespace Demo\Controller\Plugin;


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