<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Account\Service;


use Account\Entity\Criterion\AccountIsMain;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
//use Account\Entity\Criteria\AccountId;
//use Account\Entity\Criteria\AccountType;
use Account\Entity\Account;
use Base\Entity\CriterionCollection;
use Account\Entity\Criterion\AccountId;
use Account\Entity\Criterion\AccountType;

class AccountManager extends AbstractManager
{


    public function getMainAccount()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new AccountIsMain());
        return $this->getByCriterions($criterions);
    }

//    public function fetchAllMetal()
//    {
//        $criterions = new CriterionCollection();
//        $criterions->append(new AccountType(Account::TYPE_METAl));
//        return $this->fetchAllByCriterions($criterions);
//    }
//
//    public function fetchAllCurrency()
//    {
//        $criterions = new CriterionCollection();
//        $criterions->append(new AccountType(Account::TYPE_CURRENCY));
//        return $this->fetchAllByCriterions($criterions);
//    }

    protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    ) {
        switch (get_class($criterion)) {
            case AccountIsMain::class:
                $qb->andWhere($this->entityName . '.main = ' . Account::MAIN);
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
    }


}