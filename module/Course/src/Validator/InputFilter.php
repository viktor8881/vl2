<?php

namespace Course\Validator;


use Zend\I18n\Validator\IsFloat;
use Zend\InputFilter\Input;
use Zend\Validator\Date;
use Zend\Validator\DateStep;
use Zend\Validator\Digits;

class InputFilter extends \Zend\InputFilter\InputFilter
{

    public function __construct(array $data)
    {
        $id = new Input('id');
        $id->getValidatorChain()
            ->attach(new Digits());

        $date_start = new Input('date_start');
        $date_start->getValidatorChain()
            ->attach(new Date('d.m.Y'))
            ->attach(new LsDate(new \DateTime()));

        $date_end = new Input('date_end');
        $date_end->getValidatorChain()
            ->attach(new Date('d.m.Y'));

        $this->add($id)
            ->add($date_start)
            ->add($date_end)
            ->setData($data);
    }

}