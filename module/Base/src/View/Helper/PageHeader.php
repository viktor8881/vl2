<?php
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;


class PageHeader extends AbstractHelper
{

    const DEFAULT_CLASS = 'btn btn-default';

    /** @var string */
    private $title;

    /** @var string */
    private $subTitle;

    /** @var string */
    private $subTitleEscape;

    /** @var [] */
    private $buttonRight;

    /** @var [] */
    private $buttonDropDownRight;

    /** @var [] */
    private static $keysIgnore = ['name'];


    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->view->headTitle($title);
        return $this;
    }

    /**
     * @return array
     */
    public function getButtonRight()
    {
        return $this->buttonRight;
    }

    /**
     * @param array $buttonRight
     * @return $this
     */
    public function setButtonRight($buttonRight = [])
    {
        $this->buttonRight = $buttonRight;
        return $this;
    }

    /**
     * @return array
     */
    public function getButtonDropDownRight()
    {
        return $this->buttonDropDownRight;
    }

    /**
     * @param array $buttonDropDownRight
     * @return PageHeader
     */
    public function setButtonDropDownRight(array $buttonDropDownRight)
    {
        $this->buttonDropDownRight = $buttonDropDownRight;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     * @param bool $escape
     * @return $this
     */
    public function setSubTitle($subTitle, $escape = true)
    {
        $this->subTitle = $subTitle;
        $this->subTitleEscape = $escape;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $result = '';
        if (!is_null($this->title)) {
            $htmlAdd = '';
            if ($this->subTitle) {
                $htmlAdd .= $this->renderSubTitle();
            }
            if ($this->buttonRight) {
                $htmlAdd .= $this->renderButtonRightAdd();
            }
            if ($this->buttonDropDownRight) {
                $htmlAdd .= $this->renderButtonDropDownRightAdd();
            }
            $result = '<h1 class="page-header" >' . $this->view->escapeHtml($this->title) . $htmlAdd . '</h1>';
        }
        return $result;
    }

    /**
     * @return string
     */
    private function renderButtonDropDownRightAdd()
    {
        $result = '';
        if (!is_null($this->buttonDropDownRight)) {
            $result .= '<div class="pull-right">';
            $result .= '<div class="btn-group">';
            $result .= '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ' . _('Действия') . ' <span class="caret"></span></button>';
            $result .= '<ul class="dropdown-menu">';
            foreach ($this->buttonDropDownRight as $options) {
                $name = isset($options['name']) ? $options['name'] : 'Action';
                $result .= '<li><a ' . implode(' ', $this->attr($options)). '>' . _($name) . '</a></li>';
            }
            $result .= '</ul>';
            $result .= '</div>';
            $result .= '</div>';
        }
        return $result;
    }

    /**
     * @return string
     */
    private function renderSubTitle()
    {
        if ($this->subTitleEscape) {
            return $this->view->escapeHtml($this->subTitle);
        }
        return $this->subTitle;
    }

    /**
     * @return string
     */
    private function renderButtonRightAdd()
    {
        $result = '';
        if (!is_null($this->buttonRight)) {
            $options = $this->attr($this->buttonRight);
            if (!isset($options['class'])) {
                $options['class'] = '';
            } else {
                $options['class'] .= self::DEFAULT_CLASS;
            }
            $result = '<div class="pull-right">
                    <a ' . implode(' ', $this->attr($options)). ' > <span class="glyphicon glyphicon-plus"></span>' . _('Добавить') . '</a>
                </div>';
        }
        return $result;
    }

    /**
     * @param array $options
     * @return array
     */
    private function attr($options = [])
    {
        $result = [];
        foreach ($options as $key => $value) {
            if (in_array($key, self::$keysIgnore)) {
                continue;
            }
            $result[$key] = $key . '="' . $value . '"';
        }
        return $result;
    }

}
