<?php
namespace Task\Service;

use Core\Entity\AbstractCriterion;
use Core\Entity\AbstractOrder;
use Core\Entity\OrderCollection;
use Core\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Task\Entity\Criterion\ExchangeType;
use Task\Entity\Order\OrderType;
use Task\Entity\Order\OrderId;

abstract class TaskManager extends AbstractManager
{

    public function fetchAllOrderById()
    {
        $orderColl = new OrderCollection();
        $orderColl->append(new OrderId(AbstractOrder::ASC));
        return $this->fetchAll(null, $orderColl);
    }

    protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    ) {
        $result = '';
        switch (get_class($criterion)) {
            case ExchangeType::class:
                $qb->andWhere($this->entityName.'.type IN (:type)')
                    ->setParameter('type',  $criterion->getValues());
                break;
            default:
                break;
        }
        return $result;
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
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