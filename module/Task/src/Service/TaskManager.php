<?php
namespace Task\Service;

use Core\Entity\AbstractCriterion;
use Core\Entity\AbstractOrder;
use Core\Entity\OrderCollection;
use Core\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Task\Entity\Criteria\CriterionType;
use Task\Entity\Order\OrderType;
use Task\Entity\Order\OrderId;

class TaskManager extends AbstractManager
{

    public function fetchAllOrderById()
    {
        $orderColl = new OrderCollection();
        $orderColl->append(new OrderId(AbstractOrder::DESC));
        return $this->fetchAll(null, $orderColl);
    }

    protected function criterionToString(AbstractCriterion $criterion,
        QueryBuilder $qb
    ) {
        $result = '';
        switch (get_class($criterion)) {
            case CriterionType::class:
                $qb->andWhere($this->entityName.'.type IN (:type)')
                    ->setParameter('type',  $criterion->getValues());
                break;
//            case CriterionPeriod::class:
//                $qb->andWhere($this->entityName.'.dateCreate BETWEEN :start AND :end')
//                    ->setParameter('start', $criterion->getFirstValue()->format('Y-m-d'))
//                    ->setParameter('end', $criterion->getSecondValue()->format('Y-m-d'));
//                break;
//            case CriterionPercent::class:
//                $qb->andWhere($this->entityName.'.percent IN (:percent)')
//                    ->setParameter('percent',  $criterion->getValues());
//                break;
            default:
                break;
        }
        return $result;
    }

    protected function orderToString(AbstractOrder $order, QueryBuilder $qb)
    {
        switch (get_class($order)) {
            case OrderType::class:
                $qb->orderBy($this->entityName.'.type', $order->getTypeOrder());
//                $result = $prefix.'.status '.$order->getTypeOrder();
                break;
            default:
                break;
        }
    }


}