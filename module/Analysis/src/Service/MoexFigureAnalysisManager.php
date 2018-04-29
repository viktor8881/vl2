<?php

namespace Analysis\Service;

use Analysis\Entity\Criterion\CriterionDateCreated;
use Analysis\Entity\FigureAnalysisCollection;
use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;


class MoexFigureAnalysisManager extends AbstractManager
{

    /**
     * @param \DateTime $date
     * @return FigureAnalysisCollection
     */
    public function getCollectionByDate(\DateTime $date)
    {
        $coll = new FigureAnalysisCollection();
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
        $criterions->append(new CriterionDateCreated($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion,QueryBuilder $qb)
    {
        switch (get_class($criterion)) {
            case CriterionDateCreated::class:
                $qb->andWhere($this->entityName . '.created = :created')
                    ->setParameter('created', $criterion->getFirstValue()->format('Y-m-d'));
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {

    }
}