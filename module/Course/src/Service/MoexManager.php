<?php

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Course\Entity\Criterion\CriterionEqTradeDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Moex;
use Doctrine\ORM\QueryBuilder;

class MoexManager extends AbstractManager
{

    /**
     * @param \DateTime $dateTime
     * @return Moex
     */
    public function hasByDate(\DateTime $dateTime)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionEqTradeDate($dateTime));
        return $this->getByCriterions($criterions);
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
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb ) {
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id', $criterion);
                break;
            case CriterionEqTradeDate::class:
//                pr($criterion->getFirstValue()->format('Y-m-d H:i:s'));
//                pr($criterion->getFirstValue()->format(\DateTime::ISO8601));
//                exit;
                $qb->andWhere($this->entityName . '.tradeDateTime = :tradeDateTime')
                    ->setParameter('tradeDateTime', $criterion->getFirstValue()->format(\DateTime::ISO8601));
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
        // TODO: Implement addOrder() method.
    }


}