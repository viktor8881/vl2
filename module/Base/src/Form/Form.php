<?php
namespace Base\Form;


class Form extends \Zend\Form\Form
{

    private $buttonsAction = [];

    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'form-horizontal');

        if (!isset($options['token']) or false !== $options['token']) {
            // add token
            $this->add(
                [
                    'type'    => 'csrf',
                    'name'    => 'csrf',
                    'options' => [
                        'csrf_options' => [
                            'timeout' => 600
                        ]
                    ],
                ]
            );
        }
    }

    /**
     * @param array $options
     * @return $this
     */
    public function addButtonsAction(array $options)
    {
        if (!isset($options['submit'])) {
            $options['submit'] = [];
        }
        if (isset($options['submit']['name'])) {
            $buttonSubmitName = $options['submit']['name'];
            unset($options['submit']['name']);
        } else {
            $buttonSubmitName = 'buttonsubmit';
        }
        $buttonSubmitValue = 'Добавить';
        if (isset($options['submit']['value'])) {
            $buttonSubmitValue = $options['submit']['value'];
            unset($options['submit']['value']);
        }
        $buttonSubmit = array_merge(
            [
                'type'       => 'submit',
                'name'       => $buttonSubmitName,
                'attributes' => [
                    'value' => $buttonSubmitValue,
                    'class' => 'btn btn-primary'
                ],
            ], $options['submit']
        );

        if (!isset($options['cancel'])) {
            $options['cancel'] = [];
        }
        if (isset($options['cancel']['name'])) {
            $buttonCancelName = $options['cancel']['name'];
            unset($options['cancel']['name']);
        } else {
            $buttonCancelName = 'buttoncancel';
        }
        if (isset($options['cancel']['value'])) {
            $buttonCancelValue = $options['cancel']['value'];
            unset($options['cancel']['value']);
        } else {
            $buttonCancelValue = 'Отмена';
        }
        $buttonCancel = array_merge(
            [
                'type'       => 'button',
                'name'       => $buttonCancelName,
                'options'    => ['class' => 'btn'],
                'attributes' => [
                    'value'   => $buttonCancelValue,
                    'class'   => 'btn btn-link',
                    'onClick' => 'window.history.back();'
                ],
            ], $options['cancel']
        );

        $this->add($buttonCancel)
            ->add($buttonSubmit);
        $this->buttonsAction = [$this->get($buttonCancelName),
                                $this->get($buttonSubmitName)
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getButtonsAction()
    {
        return $this->buttonsAction;
    }

    public function getDataForEntity()
    {
        // TODO: Implement getDataForEntity() method.
    }


}