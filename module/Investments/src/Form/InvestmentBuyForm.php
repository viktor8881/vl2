<?php
namespace Investments\Form;

use Account\Entity\Account;
use Base\Filter\ToFloat;
use Base\Form\Element\HtmlStatic;
use Base\Form\Form;
use Base\Validator\FloatPositive;
use Base\Validator\Fraction;
use Course\Service\CourseManager;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Between;

class InvestmentBuyForm extends Form
{
    /** @var Account */
    private $account;
    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var CourseManager */
    private $courseManager;


    /**
     * InvestmentForm constructor.
     * @param float  $currentFund
     * @param Exchange[] $exchanges
     */
    public function __construct(Account $account, ExchangeManager $exchangeManager, CourseManager $courseManager)
    {
        parent::__construct('investment-form-buy');
        $this->account = $account;
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->addElements();
        $this->addInputFilter();
    }


    protected function addElements()
    {
        $currentBalance = (float)$this->account->getBalance();

        $this->add(new HtmlStatic('Доступно', $currentBalance. ' ' . $this->account->getShortNameExchange()));

        $dataExchanges = [];
        /** @var \Exchange\Entity\Exchange $item */
        foreach ($this->exchangeManager->fetchAll() as $item) {
            if ($item->getCode() == Exchange::CODE_CURRENCY_MAIN) {
                continue;
            }
            if (!isset($dataExchanges[$item->getType()])) {
                if ($item->isCurrency()) {
                    $label = 'Валюты';
                } elseif ($item->isMetal()) {
                    $label = 'Металы';
                } else {
                    $label = 'Акции';
                }
                $dataExchanges[$item->getType()] = ['label' => $label, 'options' => []];
            }
            $dataExchanges[$item->getType()]['options'][$item->getId()] =  $item->getName();
        }

        $this->add(
            [
                'type'       => 'select',
                'name'       => 'exchange_id',
                'attributes' => array(
                    'required' => 'required',
                ),
                'options'    => [
                    'label'         => 'Exchange',
                    'empty_option' => '-- Выберите Exchange --',
                    'value_options' => $dataExchanges,
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Date',
                'name'       => 'date',
                'attributes' => array(
                    'value' => date('Y-m-d'),
                    'required'  => 'required',
                ),
                'options'    => [
                    'label' => 'Дата курса',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Text',
                'name'       => 'sum_operation',
                'attributes' => array(
                    'required'  => 'required',
                    'min'       => 0,
                    'max'       => $currentBalance,
                    'maxlength' => strlen($currentBalance),
                ),
                'options'    => [
                    'label' => 'Сумма покупки',
                ],
            ]
        );

        $options = array(
            'submit' => array(
                'value' => 'Купить'
            ),
            'cancel' => array(
                'returnUrl' => '/investments'
            )
        );
        $this->addButtonsAction($options);
    }


    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add(
            [
                'name'       => 'sum_operation',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    new ToFloat()
                ],
                'validators' => [
                     new FloatPositive(),
                     new Fraction(2),
                     new Between(['min'=>1, 'max' =>$this->account->getBalance(), 'inclusive' => true]),
                ],
            ]
        );
//        $inputFilter->add(
//            [
//                'name'       => 'date',
//                'required'   => true,
//                'validators' =>  [
//                    ['name' => 'Date'],
//                    ['name' => 'LessThan',
//                        'options' => [
//                            'max' => date('Y-m-d',  strtotime('1 day')),
//                        ]
//                    ],
//                ]
//            ]
//        );
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabelSubmit($label)
    {
        $this->get('buttonsubmit')->setValue($label);
        return $this;
    }

    public function isValid()
    {
        $result = parent::isValid();
        if ($result) {
            $exchange = $this->exchangeManager->get($this->get('exchange_id')->getValue());
            $date =  new \DateTime($this->get('date')->getValue());
            $course   = $this->courseManager->getByExchangeAndDate($exchange, $date);
            if (!$course) {
                $this->setMessages(['date' => ['Курс на ' . $date->format('d.m.Y') . ' на найден. Выберите другую дату.']]);
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getDataForEntity()
    {
        $data = $this->getData();
        $exchange = $this->exchangeManager->get($data['exchange_id']);
        $course   = $this->courseManager->getByExchangeAndDate($exchange, new \DateTime($data['date']));
        $data['exchange'] = $exchange;
        $data['amount']   = $data['sum_operation'] / $course->getValue();
        $data['course']   = $course->getValue();
        $data['sum']      = $data['sum_operation'];
        $data['date']     = new \DateTime($data['date']);
        return $data;
    }



}