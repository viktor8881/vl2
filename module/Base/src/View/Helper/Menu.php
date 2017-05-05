<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;


class Menu extends AbstractHelper
{
    private $items = [];

    private $activeItemId = '';

    /**
     * Menu constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param string $activeItemId
     */
    public function setActiveItemId($activeItemId)
    {
        $this->activeItemId = $activeItemId;
    }

    /**
     * @return string
     */
    public function render()
    {
        if (count($this->items) == 0) {
            return '';
        }
        $result = '<ul class="nav navbar-nav">';
        foreach ($this->items as $item) {
            $result .= $this->renderItem($item);
        }
        $result .= '</ul>';

        return $result;
    }

    /**
     * @param array $item
     * @return string
     */
    protected function renderItem(array $item)
    {
        $id = isset($item['id']) ? $item['id'] : '';
        $isActive = ($id == $this->activeItemId);
        $label = isset($item['label']) ? _($item['label']) : '';

        $result = '';
        if (isset($item['dropdown'])) {
            $dropdownItems = $item['dropdown'];
            $result .= '<li class="dropdown' . ($isActive ? ' active' : '') . '">';
            $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            $result .= $label . ' <b class="caret"></b>';
            $result .= '</a>';

            $result .= '<ul class="dropdown-menu">';

            foreach ($dropdownItems as $item) {
                $link = isset($item['link']) ? $item['link'] : '#';
                $label = isset($item['label']) ? $item['label'] : '';

                $result .= '<li>';
                $result .= '<a href="' . $link . '">' . $label . '</a>';
                $result .= '</li>';
            }

            $result .= '</ul>';
            $result .= '</li>';
        } else {
            $link = isset($item['link']) ? $item['link'] : '#';

            $result .= $isActive ? '<li class="active">' : '<li>';
            $result .= '<a href="' . $link . '">' . $label . '</a>';
            $result .= '</li>';
        }

        return $result;
    }

}