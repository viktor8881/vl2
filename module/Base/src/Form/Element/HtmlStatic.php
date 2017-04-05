<?php
namespace Base\Form\Element;

use Zend\Form\Element;

class HtmlStatic extends Element
{

    /**
     * HtmlStatic constructor.
     *
     * @param string $label
     * @param string $value
     */
    public function __construct($label, $value)
    {
        $name = $label;
        $options = ['label' => $label];
        parent::__construct($name, $options);
        $this->setValue($value);
    }

}
