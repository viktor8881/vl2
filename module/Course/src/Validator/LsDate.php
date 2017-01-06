<?php

namespace Course\Validator;

use Zend\Validator\AbstractValidator;

class LsDate extends AbstractValidator
{

    // ID сообщений об ошибках валидации.
    const NOT_DATA  = 'notDate';
    const INVALID_DATE  = 'invalidDate';
//    const INVALID_FORMAT_LOCAL = 'invalidFormatLocal';

    // Сообщения об ошибках валидации.
    protected $messageTemplates = [
        self::NOT_DATA             => "Wrong format.",
        self::INVALID_DATE  => "%value% more than expected.",
//        self::INVALID_FORMAT_LOCAL => "Номер телефона должен быть в локальном формате",
    ];

    private $dateMax;

    public function __construct($options = null)
    {
        if ($options instanceof \DateTime) {
            $this->dateMax = $options;
        }
    }

    public function isValid($value)
    {
//        if (!($value instanceof \DateTime)) {
//            $this->error(self::NOT_DATA);
//            return false;
//        }
//        $this->setValue($value->format(DATE_ISO8601));
        if (strtotime($value) >= $this->dateMax->format('U')) {
            pr($value);
            pr(strtotime($value));
            pr($this->dateMax->format('U'));
            die('ads');
            $this->error(self::INVALID_DATE);
            return false;
        }
        return true;
    }


}