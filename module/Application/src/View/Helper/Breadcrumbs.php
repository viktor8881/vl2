<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 11.12.2016
 * Time: 6:47
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;


class Breadcrumbs extends AbstractHelper
{
    // Массив элементов.
    private $items = [];

    // Конструктор.
    public function __construct($items=[])
    {
        $this->items = $items;
    }

    // Задаем элементы.
    public function setItems($items)
    {
        $this->items = $items;
    }

    // Визуализируем навигационную цепочку.
    public function render()
    {
        if(count($this->items)==0)
            return ''; // Ничего не делать, если элементов нет.

        // Полученный HTML-код будет храниться в этой переменной
        $result = '<ol class="breadcrumb">';

        // Получаем количество элементов
        $itemCount = count($this->items);

        $itemNum = 1; // счетчик элементов

        // Проходим по элементам
        foreach ($this->items as $label=>$link) {

            // Делаем последний элемент неактивным
            $isActive = ($itemNum==$itemCount?true:false);

            // Визуализируем текущий элемент
            $result .= $this->renderItem($label, $link, $isActive);

            // Инкрементируем счетчик элементов
            $itemNum++;
        }

        $result .= '</ol>';

        return $result;
    }

    // Визуализируем элемент.
    protected function renderItem($label, $link, $isActive)
    {
        $result = $isActive?'<li class="active">':'<li>';

        if(!$isActive)
            $result .= '<a href="'.$link.'">'.$label.'</a>';
        else
            $result .= $label;

        $result .= '</li>';

        return $result;
    }

}