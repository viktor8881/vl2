<?php
/**
 * @link      http://github.com/zendframework/Balance for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Balance\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    public function __construct($balanceManager)
    {
        $this->balanceManager = $balanceManager;
    }

    public function indexAction()
    {
        $accountValue = $this->balanceManager->getValue();
        $balances = new Model_CardsBalance();
        foreach ($this->getManager('balanceCurrency')->fetchAll() as $balance) {
            if (Core_Math::compare($balance->getBalance(), 0, 6) == 0) { continue; }
            $course = $this->getManager('courseCurrency')->lastByCurrencyCode($balance->getCurrencyCode());
            $sum = $this->getManager('investmentCurrency')->getSumByBalance($balance);
            $model = new Model_CardCurrencyBalance();
            $model->setBalance($balance)
                ->setCurrentCourse($course)
                ->setSumInvest($sum);
            $balances->addBalanceCurrency($model);
        }

        foreach ($this->getManager('balanceMetal')->fetchAll() as $balance) {
            if (Core_Math::compare($balance->getBalance(), 0, 6) == 0) { continue; }
            $course = $this->getManager('courseMetal')->lastByMetalCode($balance->getMetalCode());
            $sum = $this->getManager('investmentMetal')->getSumByBalance($balance);
            $model = new Model_CardMetalBalance();
            $model->setBalance($balance)
                ->setCurrentCourse($course)
                ->setSumInvest($sum);
            $balances->addBalanceMetal($model);
        }
        $this->view->balances = $balances;


        return [];
    }
}
