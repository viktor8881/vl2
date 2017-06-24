<?php
namespace Account\Service;


use Account\Entity\Account;
use Account\Entity\AccountCollection;
use Account\Entity\Criterion\AccountIsMain;
use Account\Entity\Criterion\CriterionExchange;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;


class AccountManager extends AbstractManager
{

    /**
     * @return Account|object
     */
    public function getMainAccount()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new AccountIsMain());
        return $this->getByCriterions($criterions);
    }

    /**
     * @return float
     */
    public function getBalanceMainAccount()
    {
        $account = $this->getMainAccount();
        if ($account) {
            return $account->getBalance();
        }
    }

    /**
     * @param Exchange $exchange
     * @return Account|object
     */
    public function getByExchange(Exchange $exchange)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        return $this->getByCriterions($criterions);
    }

    /**
     * @param Exchange $exchange
     * @return float|int
     */
    public function getBalanceByExchange(Exchange $exchange)
    {
        $account = $this->getByExchange($exchange);
        return $account ? $account->getBalance() : 0;
    }

    /**
     * @return AccountCollection
     */
    public function getCollectionFetchAll()
    {
        $coll = new AccountCollection();
        foreach ($this->fetchAll() as $item) {
            $coll->append($item);
        }
        return $coll;
    }

    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb)
    {
        switch (get_class($criterion)) {
            case AccountIsMain::class:
                $qb->andWhere($this->entityName . '.main = ' . Account::MAIN);
                break;
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id', $criterion->getValues());
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
    }

}