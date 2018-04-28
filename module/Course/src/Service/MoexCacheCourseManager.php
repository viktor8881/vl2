<?php

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\OrderCollection;
use Base\Service\AbstractManager;
use Course\Entity\CacheCourseCollection;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPercent;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Entity\MoexCacheCourse;
use Course\Entity\Order\OrderId;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;
use Zend\Paginator\Adapter\NullFill;
use Zend\Paginator\Factory;


/**
 * Class CacheCourseManager
 *
 * @package Course\Service
 */
class MoexCacheCourseManager extends AbstractManager
{

    /**
     * @param \DateTime $date
     * @return MoexCacheCourse[]
     */
    public function fetchAllByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param Exchange[] $exchanges
     * @param \DateTime  $date
     * @return MoexCacheCourse[]
     */
    public function fetchAllByListIdAndDate(array $exchanges, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchanges));
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param Exchange $exchange
     * @param          $percent
     *
     * @return null|MoexCacheCourse
     */
    public function lastByExchangeAndPercent(Exchange $exchange, $percent)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPercent($percent));
        $orders = new OrderCollection();
        $orders->append(new OrderId(AbstractOrder::DESC));
        return $this->getByCriterions($criterions, $orders);
    }

    /**
     * @param \DateTime $date
     * @return mixed
     */
    public function hasByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionEqDate($date));
        return $this->countByCriterions($criterions);
    }

    /**
     * @param Exchange $exchange
     * @param  float $percent
     * @return CacheCourseCollection
     */
    public function fetch5ByExchangeAndPercent(Exchange $exchange, $percent)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPercent($percent));

        $paginator = Factory::factory(5, new NullFill());
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber(1);

        $orders = new OrderCollection();
        $orders->append(new OrderId(AbstractOrder::DESC));

        $rows = $this->fetchAllByCriterions($criterions, $paginator, $orders);
        return $this->createCollection($rows);
    }

    /**
     * @param Exchange $exchange
     * @param float $percent
     * @return CacheCourseCollection
     */
    public function fetch7ByExchangeAndPercent(Exchange $exchange, $percent)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPercent($percent));

        $paginator = Factory::factory(7, new NullFill());
        $paginator->setItemCountPerPage(7);
        $paginator->setCurrentPageNumber(1);

        $orders = new OrderCollection();
        $orders->append(new OrderId(AbstractOrder::DESC));

        $rows = $this->fetchAllByCriterions($criterions, $paginator, $orders);
        return $this->createCollection($rows);
    }

    /**
     * @param array $cacheCourses
     *
     * @return CacheCourseCollection
     */
    private function createCollection(array $cacheCourses = [])
    {
        $coll = new CacheCourseCollection();
        foreach ($cacheCourses as $cacheCourse ) {
            $coll->append($cacheCourse);
        }
        return $coll;
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb)
    {
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id', $criterion->getValues());
                break;
            case CriterionPercent::class:
                $qb->andWhere($this->entityName . '.percent IN (:percent)')
                    ->setParameter('percent', $criterion->getValues());
                break;
            case CriterionPeriod::class:
                $qb->andWhere($this->entityName . '.lastDate BETWEEN :start AND :end')
                    ->setParameter('start', $criterion->getFirstValue()->format('Y-m-d'))
                    ->setParameter('end', $criterion->getSecondValue()->format('Y-m-d'));
                break;
            case CriterionEqDate::class:
                $qb->andWhere($this->entityName . '.lastDate = :lastDate')
                    ->setParameter('lastDate', $criterion->getFirstValue()->format('Y-m-d'));
                break;
            case CriterionLsDate::class:
                $qb->andWhere($this->entityName . '.lastDate <= :lastDate')
                    ->setParameter('lastDate', $criterion->getFirstValue()->format('Y-m-d'));
                break;
        }
    }

    /**
     * @param AbstractOrder $order
     * @param QueryBuilder  $qb
     */
    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
        switch (get_class($order)) {
            case OrderId::class:
                $qb->orderBy($this->entityName . '.id', $order->getTypeOrder());
                break;
            case OrderExchange::class:
                $qb->orderBy($this->entityName . '.exchange', $order->getTypeOrder());
                break;
            default:
                break;
        }
    }


}