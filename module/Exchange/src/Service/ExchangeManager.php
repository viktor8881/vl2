<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Exchange\Service;


//use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
//use Exchange\Entity\Criteria\ExchangeId;
//use Exchange\Entity\Criteria\ExchangeType;
use Exchange\Entity\Exchange;
use Base\Entity\CriterionCollection;
use Exchange\Entity\Criterion\ExchangeId;
use Exchange\Entity\Criterion\ExchangeType;

class ExchangeManager extends AbstractManager
{

    public function fetchAllByListId(array $list)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeId($list));
        return $this->fetchAllByCriterions($criterions);
    }

    public function getMetalById($id)
    {
        $item = $this->get($id);
        if ($item && $item->isMetal()) {
            return $item;
        }
        return null;
    }

    public function getCurrencyById($id)
    {
        $item = $this->get($id);
        if ($item && $item->isCurrency()) {
            return $item;
        }
        return null;
    }

    public function fetchAllMetal()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeType(Exchange::TYPE_METAl));
        return $this->fetchAllByCriterions($criterions);
    }

    public function fetchAllCurrency()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeType(Exchange::TYPE_CURRENCY));
        return $this->fetchAllByCriterions($criterions);
    }

    protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    ) {
        switch (get_class($criterion)) {
            case ExchangeId::class:
                $qb->andWhere($this->entityName . '.id IN (:id)')
                    ->setParameter('id', $criterion->getValues());
                break;
            case ExchangeType::class:
                $qb->andWhere($this->entityName . '.type IN (:type_id)')
                    ->setParameter('type_id', $criterion->getValues());
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