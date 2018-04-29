<?php

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Entity\Moex;
use Course\Entity\MoexCollection;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;

class MoexManager extends AbstractManager
{


    /**
     * @param Exchange[]     $exchanges
     * @param \DateTime $date
     *
     *@return Course[]
     */
    public function fetchAllByExchangesAndDate(array $exchanges, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchanges));
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param \DateTime $dateTime
     * @return Moex
     */
    public function hasByDate(\DateTime $dateTime)
    {
        return count($this->fetchAllByDate($dateTime));
    }

    /**
     * @param $exchange
     * @return Moex[]
     */
    public function getByExchange($exchange)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select($this->entityName)
            ->from($this->entityName, $this->entityName)
            ->where($this->entityName . '.exchange = :exchange')
            ->setParameter('exchange', $exchange->getId());

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @param Exchange  $exchange
     * @param \DateTime $date
     * @return \Base\Entity\AbstractEntity[]
     */
    public function getCollectionByExchangeAndLsDate(Exchange $exchange, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionLsDate($date));

        $orders = new OrderCollection();
        $orders->append(new OrderId(AbstractOrder::DESC));

        $paginator = Factory::factory(20, new NullFill());
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber(1);

        $courses = $this->fetchAllByCriterions($criterions, $paginator, $orders);
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
     * @param Exchange  $exchange
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllByExchangeAndPeriod(Exchange $exchange, \DateTime $startDate, \DateTime $endDate)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPeriod([$startDate, $endDate]));
        return $this->fetchAllByCriterions($criterions);
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
        // TODO: Implement addOrder() method.
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