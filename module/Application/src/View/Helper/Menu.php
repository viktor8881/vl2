<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;


class Menu extends AbstractHelper
{
    // Массив пунктов меню.
    protected $items = [];


    // ID активного пункта.
    protected $activeItemId = '';

    // Конструктор.
    public function __construct($items=[])
    {
        $this->items = $items;
    }

    // Задаем пункты меню.
    public function setItems($items)
    {
        $this->items = $items;
    }

    // Задаем ID активных пунктов.
    public function setActiveItemId($activeItemId)
    {
        $this->activeItemId = $activeItemId;
    }

    // Визуализация меню.
    public function render()
    {
        if (count($this->items)==0)
            return ''; // Do nothing if there are no items.

        $result = '<nav class="navbar navbar-default" role="navigation">';
        $result .= '<div class="navbar-header">';
        $result .= '<button type="button" class="navbar-toggle" ';
        $result .= 'data-toggle="collapse" data-target=".navbar-ex1-collapse">';
        $result .= '<span class="sr-only">Toggle navigation</span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '<span class="icon-bar"></span>';
        $result .= '</button>';
        $result .= '</div>';

        $result .= '<div class="collapse navbar-collapse navbar-ex1-collapse">';
        $result .= '<ul class="nav navbar-nav">';

        // Визуализация элементов
        foreach ($this->items as $item) {
            $result .= $this->renderItem($item);
        }

        $result .= '</ul>';
        $result .= '</div>';
        $result .= '</nav>';

        return $result;
    }

    // Визуализирует элемент.
    protected function renderItem($item)
    {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id==$this->activeItemId);
        $label = isset($item['label']) ? $item['label'] : '';

        $result = '';

        if(isset($item['dropdown'])) {

            $dropdownItems = $item['dropdown'];

            $result .= '<li class="dropdown ' . ($isActive?'active':'') . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            $result .= $label . ' <b class="caret"></b>';
            $result .= '</a>';

            $result .= '<ul class="dropdown-menu">';

            foreach ($dropdownItems as $item) {
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';

                $result .= '<li>';
                $result .= '<a href="'.$link.'">'.$label.'</a>';
                $result .= '</li>';
            }

            $result .= '</ul>';
            $result .= '</a>';
            $result .= '</li>';

        } else {
            $link = isset($item['link']) ? $item['link'] : '#';

            $result .= $isActive?'<li class="active">':'<li>';
            $result .= '<a href="'.$link.'">'.$label.'</a>';
            $result .= '</li>';
        }

        return $result;
    }

}