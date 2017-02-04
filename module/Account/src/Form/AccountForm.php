<?php
namespace Account\Form;

use Base\Filter\ToFloat;
use Base\Form\Form;
use Base\Validator\FloatPositive;
use Base\Validator\IdentityValue;
use Zend\InputFilter\InputFilter;
use Zend\Validator\LessThan;

class AccountForm extends Form
{

    public function __construct($balance)
    {
        parent::__construct('account');

        $this->addElements($balance);
        $this->addInputFilter($balance);
    }


    protected function addElements($balance)
    {
        $this->add(
            [
                'type'       => 'text',
                'name'       => 'balance',
                'attributes' => array(
                    'required' => 'required',
                    'value'    => '10000',
                ),
                'options'    => [
                    'label' => 'Пополнить на',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'hidden',
                'name'       => 'b_token',
                'attributes' => array(
                    'required' => 'required',
                    'value'    => (float)$balance,
                ),
            ]
        );


        $options = [
            'submit' => ['value' => 'Пополнить'],
            'cancel' => ['returnUrl' => 'account']
        ];

        $this->addButtonsAction($options);
    }


    private function addInputFilter($balance)
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add(
            [
                'name'       => 'balance',
                'required'   => true,
                'filters'    => [
                    new ToFloat()
                ],
                'validators' => [
                    new FloatPositive(),
                    new LessThan($balance)
                ],
            ]
        );

        $inputFilter->add(
            [
                'name'       => 'b_token',
                'required'   => true,
                'validators' => [
                    new IdentityValue($balance),
                ],
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getValueBalance()
    {
        $data = parent::getData();
        return $data['balance'];
    }

    /**
     * @param $label
     * @return $this
     */
    public function setLabelElBalance($label)
    {
        $this->get('balance')->setLabel('Списать');
        return $this;
    }

    public function setValueBtnPrimary($value)
    {
        $this->get('buttonsubmit')->setValue('Списать');
        return $this;
    }

}