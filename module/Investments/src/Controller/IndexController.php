<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonInvestments for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Investments\Controller;


use Account\Service\AccountManager;
use Investments\Entity\Investments;
use Investments\Form\InvestmentBuyForm;
use Investments\Form\InvestmentSellForm;
use Investments\Service\InvestmentsManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


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


    public function __construct(InvestmentsManager $investmentsManager, InvestmentBuyForm $formBuy, InvestmentSellForm $formSell, AccountManager $accountManager)
    {
        $this->formBuy = $formBuy;
        $this->formSell = $formSell;
        $this->investmentsManager = $investmentsManager;
        $this->accountManager = $accountManager;
    }

    public function indexAction()
    {
        return [
            'investments' => $this->investmentsManager->fetchAll(),
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
