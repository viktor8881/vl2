<?php
namespace Investments\Form;

use Account\Entity\Account;
use Account\Service\AccountManager;
use Base\Filter\ToFloat;
use Base\Form\Element\HtmlStatic;
use Base\Form\Form;
use Base\Validator\FloatPositive;
use Base\Validator\Fraction;
use Course\Service\CourseManager;
use DoctrineORMModule\Proxy\__CG__\Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Between;

class InvestmentSellForm extends Form
{
    /** @var AccountManager */
    private $accountManager;
    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var CourseManager */
    private $courseManager;


    /**
     * InvestmentForm constructor.
     * @param float  $currentFund
     * @param Exchange[] $exchanges
     */
    public function __construct(AccountManager $accountManager, ExchangeManager $exchangeManager, CourseManager $courseManager)
    {
        parent::__construct('investment-form-sell');
        $this->accountManager = $accountManager;
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->addElements();
        $this->addInputFilter();
    }


    protected function addElements()
    {
        $accounts = $this->accountManager->getCollectionFetchAll();
//        $currentBalance = (float)$this->account->getBalance();
//
//        $this->add(new HtmlStatic('Доступно', $currentBalance. ' ' . $this->account->getShortNameExchange()));

        $dataExchanges = [];
        /** @var \Exchange\Entity\Exchange $item */
        foreach ($this->exchangeManager->fetchAll() as $item) {
            if ($item->getCode() == Exchange::CODE_CURRENCY_MAIN) {
                continue;
            }
            if (!isset($dataExchanges[$item->getType()])) {
                $label = $item->isCurrency() ? 'Валюты' : 'Металы';
                $dataExchanges[$item->getType()] = ['label' => $label, 'options' => []];
            }
            $dataExchanges[$item->getType()]['options'][$item->getId()]['value'] =  $item->getId();
            $dataExchanges[$item->getType()]['options'][$item->getId()]['label'] =  $item->getName();
            if (!$accounts->getBalanceByExchange($item)) {
                $dataExchanges[$item->getType()]['options'][$item->getId()]['disabled'] ='disabled';
            }
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

        $this->add(new HtmlStatic('Доступно', '-- Выберите Exchange --'));

        $this->add(
            [
                'type'       => 'Date',
                'name'       => 'date',
                'attributes' => array(
                    'value' => date('Y-m-d'),
                    'required'  => 'required',
                ),
                'options'    => [
                    'label' => 'Дата продажи',
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
//                    'max'       => $currentBalance,
//                    'maxlength' => strlen($currentBalance),
                ),
                'options'    => [
                    'label' => 'Сумма продажи',
                ],
            ]
        );

        $options = array(
            'submit' => array(
                'value' => 'Продать'
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
//                     new Between(['min'=>1, 'max' =>$this->account->getBalance(), 'inclusive' => true]),
                ],
            ]
        );

//        $inputFilter->add(
//            [
//                'name'       => 'exchange_id',
//                'required'   => true,
//                'validators' => [],
//            ]
//        );

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