<?php
namespace Task\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\OrderCollection;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Task\Entity\Criterion\ExchangeType;
use Task\Entity\Order\OrderId;
use Task\Entity\Order\OrderType;
use Task\Entity\Task;

abstract class TaskManager extends AbstractManager
{

    /**
     * @return Task[]
     */
    public function fetchAllOrderById()
    {
        $orderColl = new OrderCollection();
        $orderColl->append(new OrderId(AbstractOrder::ASC));
        return $this->fetchAll(null, $orderColl);
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb) {
        switch (get_class($criterion)) {
            case ExchangeType::class:
                $qb->andWhere($this->entityName.'.type IN (:type)')
                    ->setParameter('type',  $criterion->getValues());
                break;
            default:
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
        switch (get_class($order)) {
            case OrderType::class:
                $qb->orderBy($this->entityName.'.type', $order->getTypeOrder());
                break;
            default:
                break;
        }
    }

}