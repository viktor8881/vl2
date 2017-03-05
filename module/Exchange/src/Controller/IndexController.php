<?php

namespace Exchange\Controller;

use Exchange\Service\ExchangeManager as ManagerExchange;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    private $managerExchange;

    public function __construct(ManagerExchange $managerMetal)
    {
        $this->managerExchange = $managerMetal;
    }

    public function metalAction()
    {
        return new ViewModel(
            ['items' => $this->managerExchange->fetchAllMetal()]
        );
    }

    public function currencyAction()
    {
        return new ViewModel(
            ['items' => $this->managerExchange->fetchAllCurrency()]
        );
    }
}
