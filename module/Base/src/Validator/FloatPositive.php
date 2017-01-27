<?php
namespace Base\Validator;

use Zend\I18n\Validator\IsFloat;
use Zend\Validator\AbstractValidator;

class FloatPositive extends IsFloat
{
    const NOT_POSITIVE = 'notPositive';

    private $zero = null;

    /**
     * @param bollean $zero - allow zero
     * @param type    $locale
     */
    public function __construct($zero = false)
    {
        $this->zero = $zero;
        $this->messageTemplates[self::NOT_POSITIVE]
            = "Значение должно быть положительным";
        // установим локаль в en_EN чтоб разделителем была точка '.'
        parent::__construct(['locale' => 'en_EN']);
    }


    public function isValid($value)
    {
        if (parent::isValid($value) === true) {
            if ($this->zero && $value == 0) {
                return true;
            }
            if ($value <= 0) {
                parent::error(self::NOT_POSITIVE);
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

}
