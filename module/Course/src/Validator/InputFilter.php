<?php

namespace Course\Validator;


use Zend\InputFilter\Input;
use Zend\Validator\Date;
use Zend\Validator\Digits;

class InputFilter extends \Zend\InputFilter\InputFilter
{

    const FIELD_ID = 'id';
    const FIELD_DATE_START = 'date_start';
    const FIELD_DATE_END = 'date_end';

    const FORMAT_DATE = 'd.m.Y';

    public function __construct(array $data)
    {
        $id = new Input(self::FIELD_ID);
        $id->getValidatorChain()
            ->attach(new Digits());

        $date_start = new Input(self::FIELD_DATE_START);
        $date_start->getValidatorChain()
            ->attach(new Date(self::FORMAT_DATE))
            ->attach(new LsDate(new \DateTime()));

        $date_end = new Input(self::FIELD_DATE_END);
        $date_end->getValidatorChain()
            ->attach(new Date(self::FORMAT_DATE));

        $this->add($id)
            ->add($date_start)
            ->add($date_end)
            ->setData($data);
    }

}