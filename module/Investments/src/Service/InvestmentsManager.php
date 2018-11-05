<?php


namespace Investments\Service;

use Account\Entity\Account;
use Account\Service\AccountManager;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\IEntity;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Investments\Entity\Criterion\CriterionExchange;
use Investments\Entity\Criterion\InvestmentsId;
use Investments\Entity\Investments;

class InvestmentsManager extends AbstractManager
{

    /** @var AccountManager */
    private $accountManager;

    /**
     * @param AccountManager $accountManager
     * @return $this
     */
    public function setAccountManager(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
        return $this;
    }

    /**
     * @param Investments $investment
     */
    public function buy(Investments $investment)
    {
        $investment->setTypeBay();
        $this->em->persist($investment);
        $account = $this->accountManager->getMainAccount();
        $account->subBalance($investment->getSum());

        $exchange = $investment->getExchange();
        $accountExchange = $this->accountManager->getByExchange($exchange);
        if (!$accountExchange) {
            /** @var Account $accountExchange */
            $accountExchange = $this->accountManager->createEntity();
            $accountExchange->setExchange($exchange)
                            ->setMain(Account::NO_MAIN);
        }
        $accountExchange->addBalance($investment->getAmount());
        $this->em->persist($accountExchange);
        $this->em->flush();
    }

    /**
     * @param Investments $investment
     */
    public function sell(Investments $investment)
    {
        $investment->setTypeSell();
        $this->em->persist($investment);
        $account = $this->accountManager->getMainAccount();
        $account->addBalance($investment->getSum());

        $exchange = $investment->getExchange();
        $accountExchange = $this->accountManager->getByExchange($exchange);
        if (!$accountExchange) {
            /** @var Account $accountExchange */
            $accountExchange = $this->accountManager->createEntity();
            $accountExchange->setExchange($exchange)
                ->setMain(Account::NO_MAIN);
        }
        $accountExchange->subBalance($investment->getAmount());
        $this->em->persist($accountExchange);
        $this->em->flush();
    }

    /**
     * @param IEntity $model
     */
    public function delete(IEntity $model)
    {
        if (!($model instanceof Investments)) {
            throw new \RuntimeException('Wrong type. Expected type Investments');
        }
        /** @var Investments $model */
        $accountMain = $this->accountManager->getMainAccount();
        $accountExchange = $this->accountManager->getByExchange($model->getExchange());
        if ($model->isBay()) {
            $accountMain->addBalance($model->getSum());
            $accountExchange->subBalance($model->getAmount());
        } else {
            $accountMain->subBalance($model->getSum());
            $accountExchange->addBalance($model->getAmount());
        }
        $this->em->remove($model);
        $this->em->flush();
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb) {
        switch (get_class($criterion)) {
            case InvestmentsId::class:
                $qb->andWhere($this->entityName . '.id IN (:id)')
                    ->setParameter('id', $criterion->getValues());
                break;
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange)')
                    ->setParameter('exchange', $criterion->getValues());
                break;
            default:
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
//        switch (get_class($order)) {
//            case 'Question_Order_Status':
//                $result = $prefix.'.status '.$order->getTypeOrder();
//                break;
//            default:
//                break;
//        }
    }


}