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
            ->attach(new Date('d.m.Y'));

        $date_end = new Input('date_end');
        $date_end->getValidatorChain()
            ->attach(new DateStep(['format' => 'd.m.Y', 'baseValue' => date('d.m.Y')]));

        $percent = new Input('percent');
        $percent->getValidatorChain()
            ->attach(new IsFloat());

        $this->add($id)
            ->add($date_start)
            ->add($date_end)
            ->add($percent)
            ->setData($data);
    }

    public function getStringMessages()
    {
        $result = '';
        foreach ($this->getMessages() as $mess) {
            $result .= implode(" ", $mess);
        }
        return $result;
    }

}