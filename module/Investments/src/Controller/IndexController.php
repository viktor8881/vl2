<?php

namespace Investments\Controller;


use Account\Service\AccountManager;
use Base\Entity\CriterionCollection;
use Exchange\Service\ExchangeManager;
use Investments\Entity\Criterion\CriterionExchange;
use Investments\Entity\Investments;
use Investments\Form\InvestmentBuyForm;
use Investments\Form\InvestmentSellForm;
use Investments\Service\InvestmentsManager;
use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController
{

    /** @var InvestmentBuyForm */
    private $formBuy;
    /** @var InvestmentSellForm */
    private $formSell;
    /** @var InvestmentsManager */
    private $investmentsManager;
    /** @var AccountManager */
    private $accountManager;
    /** @var ExchangeManager */
    private $exchangeManager;


    public function __construct(InvestmentsManager $investmentsManager, InvestmentBuyForm $formBuy, InvestmentSellForm $formSell, AccountManager $accountManager, ExchangeManager $exchangeManager)
    {
        $this->formBuy = $formBuy;
        $this->formSell = $formSell;
        $this->investmentsManager = $investmentsManager;
        $this->accountManager = $accountManager;
        $this->exchangeManager = $exchangeManager;
    }

    public function indexAction()
    {
        $criterions = new CriterionCollection();
        $exchange = $this->exchangeManager->get((int)$this->params()->fromRoute('id', -1));
        if ($exchange) {
            $criterions->append(new CriterionExchange($exchange));
        }
        return [
            'investments' => $this->investmentsManager->fetchAllByCriterions($criterions),
            'mainAccount' => $this->accountManager->getMainAccount()
        ];
    }


    public function buyAction()
    {
        $form = $this->formBuy;
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getDataForEntity();
                /** @var Investments $investment */
                $investment = $this->investmentsManager->createEntity($data);
                $investment->setTypeBay();

                $this->investmentsManager->buy($investment);
                return $this->redirect()->toRoute('investments');
            }
        }
        return ['form' => $form];
    }


    public function sellAction()
    {
        $form = $this->formSell;
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getDataForEntity();
                /** @var Investments $investment */
                $investment = $this->investmentsManager->createEntity($data);
                $investment->setTypeSell();

                $this->investmentsManager->sell($investment);
                return $this->redirect()->toRoute('investments');
            }
        }
        return ['form' => $form];
    }


    public function deleteAction()
    {
        /** @var Investments $investment */
        $investment = $this->investmentsManager->get((int)$this->params()->fromRoute('id', -1));
        if (!$investment) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }
        $this->investmentsManager->delete($investment);
        return $this->redirect()->toRoute('investments');
    }



}
