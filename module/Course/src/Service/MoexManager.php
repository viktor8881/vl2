<?php

namespace Course\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\OrderCollection;
use Base\Service\AbstractManager;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Entity\Moex;
use Course\Entity\MoexCollection;
use Course\Entity\Order\OrderExchange;
use Course\Entity\Order\OrderTradeDateTime;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;
use Zend\Paginator\Adapter\NullFill;
use Zend\Paginator\Factory;

class MoexManager extends AbstractManager
{

    /**
     * @param $exchangeId
     * @return null|Moex
     */
    public function lastByExchangeId($exchangeId)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchangeId));

        $order = new OrderCollection();
        $order->append(new OrderTradeDateTime(AbstractOrder::DESC));

        return $this->getByCriterions($criterions, $order);
    }

    /**
     * @param Exchange[]     $exchanges
     * @param \DateTime $date
     *
     *@return Course[]
     */
    public function fetchAllByExchangesAndDate(array $exchanges, \DateTime $date)
    {
        return $this->fetchAllByExchangesAndPeriod($exchanges, $date, clone $date);
    }

    /**
     * @param \DateTime $dateTime
     * @return Moex
     */
    public function hasByDate(\DateTime $dateTime)
    {
        return count($this->fetchAllByPeriod($dateTime, clone $dateTime));
    }

    /**
     * @param $exchange
     * @return Moex[]
     */
    public function fetchAllByExchangeId($id)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($id));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param Exchange  $exchange
     * @param \DateTime $date
     * @return \Base\Entity\AbstractEntity[]
     */
    public function getCollectionByExchangeAndLsDate(Exchange $exchange, \DateTime $date)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Moex::class, 'mc');
        $rsm->addFieldResult('mc', 'id', 'id');

        $rsm->addFieldResult('mc', 'secid', 'secId');
        $rsm->addFieldResult('mc', 'rate', 'rate');
        $rsm->addFieldResult('mc', 'trade_date_time', 'tradeDateTime');
        $rsm->addJoinedEntityResult(Exchange::class , 'e', 'mc', 'exchange');
        $rsm->addFieldResult('e', 'exchange_id', 'id');
        $rsm->addFieldResult('e', 'type', 'type');
        $rsm->addFieldResult('e', 'code', 'code');
        $rsm->addFieldResult('e', 'name', 'name');
        $rsm->addFieldResult('e', 'short_name', 'shortName');

        $sql = 'SELECT mc.id, mc.secid, AVG(mc.`rate`)as `rate`, mc.`trade_date_time`,
                      e.id as exchange_id, e.type, e.code, e.name, e.short_name
                  FROM `moex_course` as mc INNER JOIN exchange AS e ON mc.exchange_id = e.id 
                  WHERE mc.`exchange_id`=? AND mc.trade_date_time < ? GROUP BY DATE(mc.`trade_date_time`) ORDER BY mc.`trade_date_time` DESC LIMIT 20';

        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $exchange->getId());
        $query->setParameter(2, $date->format(\DateTime::ISO8601));

        $courses = $query->getResult();
        return $this->createCollection($courses);
    }

    /**
     * @param \DateTime $date
     *
     * @return Course[]
     */
    public function fetchAllByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param [Exchange]  $exchanges
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllByExchangesAndPeriod(array $exchanges, \DateTime $startDate, \DateTime $endDate)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchanges));
        $criterions->append(new CriterionPeriod([$startDate, $endDate]));

        $paginator = Factory::factory(100, new NullFill());
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber(1);
        return $this->fetchAllByCriterions($criterions, $paginator);
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllByPeriod(\DateTime $startDate, \DateTime $endDate)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionPeriod([$startDate, $endDate]));

        $paginator = Factory::factory(100, new NullFill());
        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber(1);
        return $this->fetchAllByCriterions($criterions, $paginator);
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb ) {
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id', $criterion->getValues());
                break;
            case CriterionPeriod::class:
                $qb->andWhere($this->entityName . '.tradeDateTime BETWEEN :start AND :end')
                    ->setParameter('start', $criterion->getFirstValue()->format('Y-m-d 00:00:00'))
                    ->setParameter('end', $criterion->getSecondValue()->format('Y-m-d 23:59:59'));
                break;
            case CriterionEqDate::class:
                /** @var $date \DateTime */
                $date = $criterion->getFirstValue();
                $date->setTime(18,30);
                $qb->andWhere($this->entityName . '.tradeDateTime = :tradeDateTime')
                    ->setParameter('tradeDateTime', $date->format(\DateTime::ISO8601));
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
        switch (get_class($order)) {
            case OrderTradeDateTime::class:
                $qb->addOrderBy($this->entityName.'.tradeDateTime', $order->getTypeOrder());
                break;
            case OrderExchange::class:
                $qb->addOrderBy($this->entityName . '.exchange', $order->getTypeOrder());
                break;
            default:
                break;
        }
    }

    /**
     * @param array $courses
     * @return MoexCollection
     */
    private function createCollection(array $courses)
    {
        $coll = new MoexCollection();
        foreach ($courses as $course) {
            $coll->append($course);
        }
        return $coll;
    }


}