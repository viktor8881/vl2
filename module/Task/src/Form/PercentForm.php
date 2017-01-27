<?php
namespace Task\Form;

use Core\Filter\ToFloat;
use Core\Form\Form;
use Core\Validator\FloatPositive;
use Exchange\Service\ExchangeManager;
use Task\Entity\Task;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilter;

class PercentForm extends Form
{

    private $metals;
    private $currencies;


    public function __construct(array $listMetal, array $listCurrency)
    {
        $this->metals = $listMetal;
        $this->currencies = $listCurrency;

        parent::__construct('task_percent-form');

        $this->addElements();
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
                ),
                'options'    => [
                    'label'         => 'Mode',
                    'value_options' => $modes,
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'text',
                'name'       => 'percent',
                'attributes' => array(
                    'required' => 'required',
                ),
                'options'    => [
                    'label' => 'Percent',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Number',
                'name'       => 'period',
                'attributes' => array(
                    'required'  => 'required',
                    'min'       => 0,
                    'max'       => 99,
                    'maxlength' => 2,
                ),
                'options'    => [
                    'label' => 'Period',
                ],
            ]
        );

        $valuesMetal = [];
        foreach ($this->metals as $item) {
            $valuesMetal[] = ['value' => $item->getId(),
                              'label' => $item->getName(),
            ];
        }
        $this->add(
            [
                'type'    => 'MultiCheckbox',
                'name'    => 'metals',
                'options' => [
                    'label'         => 'Metals',
                    'value_options' => $valuesMetal,
                ],
            ]
        );

        $valuesCurrency = [];
        foreach ($this->currencies as $item) {
            $valuesCurrency[] = ['value' => $item->getId(),
                                 'label' => $item->getName(),
            ];
        }

        $this->add(
            [
                'type'    => 'MultiCheckbox',
                'name'    => 'currencies',
                'options' => [
                    'label'         => 'Metals',
                    'value_options' => $valuesCurrency,
                ],
            ]
        );

        $options = array(
            'cancel' => array(
                'returnUrl' => '/tasks/index/list'
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
            $countInvest = count($this->get('metals')->getValue()) + count(
                    $this->get('currencies')->getValue()
                );
            if ($countInvest == 0) {
                $this->get('metals')->setMessages(
                    ['Select metals or currencies.']
                );
                $this->get('currencies')->setMessages(
                    ['Select metals or currencies.']
                );
                $isValid = false;
            }
        }
        return $isValid;
    }

    /**
     * @return array
     */
    public function getDataForItem()
    {
        $result = [];
        $data = parent::getData();
        $result['mode'] = $data['mode'];
        $result['percent'] = $data['percent'];
        $result['period'] = $data['period'];
        if (!is_array($data['metals'])) {
            $data['metals'] = [];
        }
        if (!is_array($data['currencies'])) {
            $data['currencies'] = [];
        }
        $result['listIdExchanges'] = array_merge($data['metals'], $data['currencies']);
        return $result;
    }

}