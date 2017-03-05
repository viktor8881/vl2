<?php

namespace Analysis\Service;

use Analysis\Entity\Criterion\OrderDateCreated;
use Analysis\Entity\TaskPercentAnalysisCollection;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;


class TaskPercentAnalysisManager extends AbstractManager
{

    /**
     * @param \DateTime $date
     * @return TaskPercentAnalysisCollection
     */
    public function getCollectionByDate(\DateTime $date)
    {
        $coll = new TaskPercentAnalysisCollection();
        foreach ($this->fetchAllByDate($date) as $item) {
            $coll->append($item);
        }
        return $coll;
    }

    /**
     * @param \DateTime $date
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new OrderDateCreated($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion,QueryBuilder $qb)
    {
        switch (get_class($criterion)) {
            case OrderDateCreated::class:
                $qb->andWhere($this->entityName . '.created = :created')
                    ->setParameter('created', $criterion->getFirstValue());
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {

    }

}