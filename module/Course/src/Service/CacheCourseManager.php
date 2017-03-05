<?php

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\OrderCollection;
use Base\Service\AbstractManager;
use Course\Entity\CacheCourse;
use Course\Entity\CacheCourseCollection;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPercent;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Entity\Order\CourseId;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;
use Zend\Paginator\Adapter\NullFill;
use Zend\Paginator\Factory;


/**
 * Class CacheCourseManager
 *
 * @package Course\Service
 */
class CacheCourseManager extends AbstractManager
{

    /**
     * @param \DateTime $date
     * @return CacheCourse[]
     */
    public function fetchAllByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param int[] $list
     * @param \DateTime $date
     * @return CacheCourse[]
     */
    public function fetchAllByListIdAndDate(array $list, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($list));
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param Exchange $exchange
     * @param          $percent
     *
     * @return null|CacheCourse
     */
    public function lastByExchangeAndPercent(Exchange $exchange, $percent)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPercent($percent));
        return $this->getByCriterions($criterions);
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
        return $this->collectionByParams($exchange, $percent, 5);
    }

    /**
     * @param Exchange $exchange
     * @param float $percent
     * @return CacheCourseCollection
     */
    public function fetch7ByCodePercent(Exchange $exchange, $percent)
    {
        return $this->collectionByParams($exchange, $percent, 7);
    }

    /**
     * @param Exchange $exchange
     * @param float $percent
     * @param int $count
     * @return CacheCourseCollection
     */
    private function collectionByParams(Exchange $exchange, $percent, $count)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPercent($percent));

        $paginator = Factory::factory($count, new NullFill());
        $paginator->setItemCountPerPage($count);
        $paginator->setCurrentPageNumber(1);

        $orders = new OrderCollection();
        $orders->append(new CourseId('Desc'));

        $coll = new CacheCourseCollection();
        foreach ($this->fetchAllByCriterions($criterions, $paginator, $orders) as $cacheCourse ) {
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
            case CriterionPeriod::class:
                $qb->andWhere($this->entityName . '.lastDate BETWEEN :start AND :end')
                    ->setParameter('start', $criterion->getFirstValue())
                    ->setParameter('end', $criterion->getSecondValue());
                break;
            case CriterionEqDate::class:
                $qb->andWhere($this->entityName . '.lastDate = :lastDate')
                    ->setParameter('lastDate', $criterion->getFirstValue());
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
            case CourseId::class:
                $qb->orderBy($this->entityName . '.id', $order->getTypeOrder());
                break;
            default:
                break;
        }
    }


}