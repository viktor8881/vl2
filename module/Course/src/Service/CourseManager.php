<?php
namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\OrderCollection;
use Base\Service\AbstractManager;
use Course\Entity\Course;
use Course\Entity\CourseCollection;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionLsDate;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Entity\Order\OrderExchange;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;
use Task\Entity\Order\OrderId;
use Zend\Paginator\Factory;
use Zend\Paginator\Adapter\NullFill;

/**
 * Class CourseManager
 *
 * @package Course\Service
 */
class CourseManager extends AbstractManager
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
     * @param \DateTime $date
     * @return bool
     */
    public function hasByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionEqDate($date));
        return (bool)$this->countByCriterions($criterions);
    }

    /**
     * @param Course[] $courses
     */
    public function insertList(array $courses) {
        if (count($courses)) {
            $i = 0;
            $batchSize = 30;
            foreach ($courses as $course) {
                $this->em->persist($course);
                if ((++$i % $batchSize) === 0) {
                    $this->em->flush();
                    $this->em->clear();
                }
            }
            $this->em->flush();
            $this->em->clear();
        }
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
     * @param Exchange  $exchange
     * @param \DateTime $date
     * @return \Base\Entity\AbstractEntity[]
     */
    public function getCollectionByExchangeAndDate(Exchange $exchange, \DateTime $date)
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
     * @param Course[] $courses
     * @return CourseCollection
     */
    private function createCollection(array $courses)
    {
        $coll = new CourseCollection();
        foreach ($courses as $course) {
            $coll->append($course);
        }
        return $coll;
    }


    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder  $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb) {
        switch (get_class($criterion)) {
            case CriterionExchange::class:
                $qb->andWhere($this->entityName . '.exchange IN (:exchange_id)')
                    ->setParameter('exchange_id', $criterion->getValues());
                break;
            case CriterionPeriod::class:
                $qb->andWhere($this->entityName . '.dateCreate BETWEEN :start AND :end')
                    ->setParameter('start', $criterion->getFirstValue()->format('Y-m-d'))
                    ->setParameter('end', $criterion->getSecondValue()->format('Y-m-d'));
                break;
            case CriterionEqDate::class:
                $qb->andWhere($this->entityName . '.dateCreate = :dateCreate')
                    ->setParameter('dateCreate', $criterion->getFirstValue()->format('Y-m-d'));
                break;
            case CriterionLsDate::class:
                $qb->andWhere($this->entityName . '.dateCreate <= :dateCreate')
                    ->setParameter('dateCreate', $criterion->getFirstValue()->format('Y-m-d'));
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