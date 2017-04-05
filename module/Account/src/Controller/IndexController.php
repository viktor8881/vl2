<?php
/**
 * @link      http://github.com/zendframework/Account for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Account\Controller;

use Account\Entity\Account;
use Account\Form\AccountForm;
use Account\Service\AccountManager;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    private $accountManager;
    private $exchangeManager;

    public function __construct(AccountManager $accountManager,
        ExchangeManager $exchangeManager
    ) {
        $this->accountManager = $accountManager;
        $this->exchangeManager = $exchangeManager;
    }

    public function indexAction()
    {
        $main = null;
        $metalAccounts = $currencyAccounts = [];
        /** @var Account $account */
        foreach ($this->accountManager->fetchAll() as $account) {
            if ($account->isMain()) {
                $main = $account;
            } elseif ($account->isMetal()) {
                $metalAccounts[] = $account;
            } else {
                $currencyAccounts[] = $account;
            }
        }
        return ['main'             => $main,
                'metalAccounts'    => $metalAccounts,
                'currencyAccounts' => $currencyAccounts];
    }

    public function addAction()
    {
        /** @var Account $mainAcc */
        $mainAcc = $this->accountManager->getMainAccount();
        $balanceMainAcc = 0;
        if ($mainAcc) {
            $balanceMainAcc = $mainAcc->getBalance();
        }
        $form = new AccountForm($balanceMainAcc);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $mainAcc->addBalance($form->getValueBalance());
                $this->accountManager->update($mainAcc);

                $this->flashMessenger()->addSuccessMessage('Balance refilled successfully');
                return $this->redirect()->toRoute('account');
            }
        }
        return ['form'    => $form,
                'account' => $mainAcc];
    }

    public function subAction()
    {
        $mainAcc = $this->accountManager->getMainAccount();
        $balanceMainAcc = 0;
        if ($mainAcc) {
            $balanceMainAcc = $mainAcc->getBalance();
        }
        $form = new AccountForm($balanceMainAcc);
        $form->setLabelElBalance('Списать')
            ->setValueBtnPrimary('Списать');

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                /** @var Account $mainAcc */
                $mainAcc->subBalance($form->getValueBalance());
                $this->accountManager->update($mainAcc);

                $this->flashMessenger()->addSuccessMessage('Balance subtraction successfully');
                return $this->redirect()->toRoute('account');
            }
        }
        return ['form'    => $form,
                'account' => $mainAcc];
    }

}
