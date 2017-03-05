<?php
namespace Account\Service;


use Account\Entity\Account;
use Account\Entity\Criterion\AccountIsMain;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;

class AccountManager extends AbstractManager
{

    /**
     * @return null|object
     */
    public function getMainAccount()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new AccountIsMain());
        return $this->getByCriterions($criterions);
    }

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