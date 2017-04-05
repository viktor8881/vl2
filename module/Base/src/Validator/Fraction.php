<?php
namespace Base\Validator;

use Zend\Validator\AbstractValidator;

class Fraction extends AbstractValidator
{
    const INVALID = 'countInvalid';

    protected $_messageTemplates
        = array(
            self::INVALID => "Допустимо только '%value%' знака в дробной части."
        );

    protected $_fraction = 2;

    /**
     * настройка параметров
     *
     * @param array $params - массив параметров
     */
    public function __construct($params)
    {
        if (is_array($params) && isset($params['countNum'])) {
            $this->_fraction = (int)$params['countNum'];
        } elseif (is_string($params) or is_int($params)) {
            $this->_fraction = (int)$params;
        }
        $this->setValue($this->_fraction);
        parent::__construct();
    }

    /**
     *  валидация параметра
     *
     * @param int $value
     * @return boolean
     */
    public function isValid($value)
    {
        if ($value) {
            $value = (float)$value;
            $partsNum = explode('.', $value, 2);
            if (isset($partsNum[1]) && strlen($partsNum[1]) > $this->_fraction) {
                $this->error(self::INVALID);
                return false;
            }
        }
        return true;
    }

}
