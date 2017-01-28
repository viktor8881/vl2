<?php

namespace Course\Validator;

use Zend\Validator\AbstractValidator;

class LsDate extends AbstractValidator
{

    const NOT_DATA  = 'notDate';
    const INVALID_DATE  = 'invalidDate';

    protected $messageTemplates = [
        self::NOT_DATA             => "Wrong format.",
        self::INVALID_DATE  => "%value% more than expected.",
    ];

    private $dateMax;

    public function __construct(\DateTime $options = null)
    {
        if (!is_null($options)) {
            $this->dateMax = $options;
        }
    }

    public function isValid($value)
    {
        if (strtotime($value) > $this->dateMax->format('U')) {
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