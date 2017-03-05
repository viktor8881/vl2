<?php
namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Service\AbstractManager;
use Course\Entity\Course;
use Course\Entity\Criterion\CriterionEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;

/**
 * Class CourseManager
 *
 * @package Course\Service
 */
class CourseManager extends AbstractManager
{

    /**
     * @param int[] $list
     * @param \DateTime $date
     * @return Course[]
     */
    public function fetchAllByListIdAndDate(array $list, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($list));
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
     * @param Course[] $listCourse
     */
    public function insertList(array $listCourse) {
        if (count($listCourse)) {
            $i = 0;
            $batchSize = 30;
            foreach ($listCourse as $course) {
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
     * @param \DateTime $dateLater
     * @param \DateTime $date
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllByExchangeAndPeriod(Exchange $exchange, \DateTime $dateLater, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionPeriod([$dateLater, $date]));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param Exchange  $exchange
     * @param \DateTime $date
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllByExchangeAndDate(Exchange $exchange, \DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CriterionExchange($exchange));
        $criterions->append(new CriterionEqDate($date));
        return $this->fetchAllByCriterions($criterions);
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
                    ->setParameter('start', $criterion->getFirstValue())
                    ->setParameter('end', $criterion->getSecondValue());
                break;
            case CriterionEqDate::class:
                $qb->andWhere($this->entityName . '.dateCreate = :dateCreate')
                    ->setParameter('dateCreate', $criterion->getFirstValue());
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