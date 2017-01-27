<?php
namespace Base\Form\Element;

use Zend\Form\Element\Button;

class ButtonCancel extends Button
{

    public function   __construct($spec, $options = null) {        
        if (!isset($options['class'])) {
            $options['class'] = 'btn';
        }
        if (!isset($options['label'])) {
            $options['label'] = 'Отмена';
        }
        if (!isset($options['onclick'])) {
            if (empty($options['returnUrl'])) {
                $options['onclick'] = 'history.back();';
            }else{
                $options['onclick'] = 'window.location.href = "'.$options['returnUrl'].'";';
                unset($options['returnUrl']);
            }
        }
        parent::__construct($spec, $options);
    }

}
