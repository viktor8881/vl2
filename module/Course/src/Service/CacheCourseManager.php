<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Course\Service;


use Core\Entity\AbstractCriterion;
use Core\Entity\AbstractOrder;
use Course\Entity\Criteria\CriterionExchange;
use Course\Entity\Criteria\CriterionPercent;
use Course\Entity\Criteria\CriterionPeriod;
use Core\Service\AbstractManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class CacheCourseManager extends AbstractManager
{

    protected function criterionToString(AbstractCriterion $criterion, QueryBuilder $qb)
    {
        $result = '';
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName.'.id IN (:id)')
                    ->setParameter('id',  $criterion->getValues());
                break;
            case CriterionPeriod::class:
                $qb->andWhere($this->entityName.'.lastDate BETWEEN :start AND :end')
                    ->setParameter('start', $criterion->getFirstValue()->format('Y-m-d'))
                    ->setParameter('end', $criterion->getSecondValue()->format('Y-m-d'));
                break;
            case CriterionPercent::class:
                $qb->andWhere($this->entityName.'.percent IN (:percent)')
                    ->setParameter('percent',  $criterion->getValues());
                break;
            default:
                break;
        }
        return $result;
    }

    protected function orderToString(AbstractOrder $order, QueryBuilder $qb)
    {
        $result = '';
//        switch (get_class($order)) {
//            case 'Question_Order_Status':
//                $result = $prefix.'.status '.$order->getTypeOrder();
//                break;
//            default:
//                break;
//        }
        return $result;
    }


}