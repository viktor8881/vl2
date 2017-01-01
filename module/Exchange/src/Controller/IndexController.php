<?php

namespace Exchange\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Model\Exchange\Manager as ManagerExchange;
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
        return new ViewModel(['items' => $this->managerExchange->fetchAllMetal()]);
    }

    public function currencyAction()
    {
        return new ViewModel(['items' => $this->managerExchange->fetchAllCurrency()]);
    }
}
