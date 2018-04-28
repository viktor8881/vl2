<?php

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Moex;
use Doctrine\ORM\QueryBuilder;

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
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb ) {
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id', $criterion->getValues());
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


}