<?php


namespace Investments\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Investments\Entity\Criterion\InvestmentsId;

class InvestmentsManager extends AbstractManager
{

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