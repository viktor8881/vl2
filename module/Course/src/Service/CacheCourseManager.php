<?php

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Course\Entity\CacheCourse;
use Course\Entity\Criterion\CourseEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPercent;
use Course\Entity\Criterion\CriterionPeriod;
use Base\Service\AbstractManager;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;


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
        $criterions->append(new CourseEqDate($date));
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
        $criterions->append(new CourseEqDate($date));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param Exchange $exchange
     * @param          $percent
     *
     * @return CacheCourse|null
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
        $criterions->append(new CourseEqDate($date));
        return $this->countByCriterions($criterions);
    }

//    /**
//     * @param Course[] $listCourse
//     * @return bool
//     */
//    public function insertList(array $listCourse) {
//        if (count($listCourse)) {
//            $i = 0;
//            $batchSize = 30;
//            foreach ($listCourse as $course) {
//                $this->em->persist($course);
//                if ((++$i % $batchSize) === 0) {
//                    $this->em->flush();
//                    $this->em->clear();
//                }
//            }
//            $this->em->flush();
//            $this->em->clear();
//        }
//        return true;
//    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    ) {
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
            case CourseEqDate::class:
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
//        switch (get_class($order)) {
//            case 'Question_Order_Status':
//                $result = $prefix.'.status '.$order->getTypeOrder();
//                break;
//            default:
//                break;
//        }
    }


}