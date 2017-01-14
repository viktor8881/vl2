<?php
namespace Task\Form;

use Core\Filter\ToFloat;
use Core\Validator\FloatPositive;
use Exchange\Service\ExchangeManager;
use Task\Entity\Task;
use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\MultiCheckbox;

class PercentForm extends Form
{

    private $exchangeManager;

    public function __construct(ExchangeManager $exchangeManager)
    {
        $this->exchangeManager = $exchangeManager;

        parent::__construct('task_percent-form');
        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'form-horizontal');

        $this->addElements();
//            $this->get('metals')->setRequired(false);;
        //        $this->get('currencies')->setRequired(false);;
//        $checkbox = new MultiCheckbox('sss');
//        $checkbox->setChecked()getInputSpecification();
        $this->addInputFilter();
    }


    protected function addElements()
    {
        $modes = [Task::MODE_ONLY_UP   => 'Only Up investment',
                  Task::MODE_ONLY_DOWN => 'Only Down investment',
                  Task::MODE_UP_DOWN   => 'Up or Down investment'];
        $this->add(
            [
                'type'       => 'select',
                'name'       => 'mode',
                'attributes' => array(
                    'required' => 'required',
                    'class'    => 'form-control'
                ),
                'options'    => [
                    'label'            => 'Mode',
                    'value_options'    => $modes,
                    'label_attributes' => ['class' => 'col-sm-3 control-label'],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'text',
                'name'       => 'percent',
                'attributes' => array(
                    'required' => 'required',
                    'class'    => 'form-control'
                ),
                'options'    => [
                    'label'            => 'Percent',
                    'label_attributes' => ['class' => 'col-sm-3 control-label'],
                ],
            ]
        );


        $this->add(
            [
                'type'       => 'Number',
                'name'       => 'period',
                'attributes' => array(
                    'required'  => 'required',
                    'class'     => 'form-control',
                    'min'       => 0,
                    'max'       => 99,
                    'maxlength' => 2,
                ),
                'options'    => [
                    'label'            => 'Period',
                    'label_attributes' => ['class' => 'col-sm-3 control-label'],
                ],
            ]
        );

        $valuesMetal = [];
        foreach ($this->exchangeManager->fetchAllMetal() as $item) {
            $valuesMetal[] = ['value'            => $item->getId(),
                              'label'            => $item->getName(),
                              'label_attributes' => array(
                                  'class' => 'checkbox',
                              ),];
        }
        $this->add(
            [
                'type'    => 'MultiCheckbox',
                'name'    => 'metals',
                'options' => [
                    'label'            => 'Metals',
                    'label_attributes' => ['class' => 'col-sm-3 control-label'],
                    'value_options'    => $valuesMetal,
                ],
            ]
        );

        $valuesCurrency = [];
        foreach ($this->exchangeManager->fetchAllCurrency() as $item) {
            $valuesCurrency[] = ['value'            => $item->getId(),
                                 'label'            => $item->getName(),
                                 'label_attributes' => array(
                                     'class' => 'checkbox',
                                 ),];
        }

        $this->add(
            [
                'type'    => 'MultiCheckbox',
                'name'    => 'currencies',
                'options' => [
                    'label'            => 'Metals',
                    'label_attributes' => ['class' => 'col-sm-3 control-label'],
                    'value_options'    => $valuesCurrency,
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'submit',
                'name'       => 'submit',
                'attributes' => [
                    'value' => 'Create',
                    'class' => 'btn btn-primary'
                ],
            ]
        );
    }


    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add(
            [
                'name'       => 'percent',
                'required'   => true,
                'filters'    => [
                    new ToFloat()
                ],
                'validators' => [
                    new FloatPositive()
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'period',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'Digits'],
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'metals',
                'required'   => false,
                'validators' => [],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'currencies',
                'required'   => false,
                'validators' => [],
            ]
        );
    }

    public function isValid()
    {
        $isValid = parent::isValid();
        if ($isValid == true) {
            $countInvest = count($this->get('metals')->getValue()) + count($this->get('currencies')->getValue());
            if ($countInvest == 0) {
                $this->get('metals')->setMessages(['Select metals or currencies.']);
                $this->get('currencies')->setMessages(['Select metals or currencies.']);
                $isValid = false;
            }
        }
        return $isValid;
    }

}