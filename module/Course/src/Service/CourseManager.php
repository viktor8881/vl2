<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPercent;
use Course\Entity\Criterion\CriterionPeriod;
use Base\Service\AbstractManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class CourseManager extends AbstractManager
{

    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb)
    {
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName.'.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id',  $criterion->getValues());
                break;
            case CriterionPeriod::class:
                $qb->andWhere($this->entityName.'.dateCreate BETWEEN :start AND :end')
                    ->setParameter('start', $criterion->getFirstValue()->format('Y-m-d'))
                    ->setParameter('end', $criterion->getSecondValue()->format('Y-m-d'));
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