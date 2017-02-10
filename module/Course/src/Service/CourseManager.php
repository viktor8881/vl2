<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Course\Entity\Course;
use Course\Entity\Criterion\CourseEqDate;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPercent;
use Course\Entity\Criterion\CriterionPeriod;
use Base\Service\AbstractManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Exchange;

class CourseManager extends AbstractManager
{
    const URL_CURRENCY_COURSES = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';
    const URL_METAL_COURSES = 'http://www.cbr.ru/scripts/xml_metall.asp?date_req1=%date%&date_req2=%date%';


    public function hasByDate(\DateTime $date)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new CourseEqDate($date->format('Y-m-d')));
        return $this->countByCriterions($criterions);
    }

    /**
     * @param Course[] $listCourse
     * @return bool
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
        return true;
    }

    public function receiveByDateToArray(\DateTime $date)
    {
        $result = [];
        $xmlstr = file_get_contents(
            self::URL_CURRENCY_COURSES . $date->format('d/m/Y')
        );
        $simpleXml = new \SimpleXMLElement($xmlstr);
        if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
            foreach ($simpleXml->Valute as $item) {
                $result[(string)$item['ID']] = [
                    'value'   => str_replace(',', '.', (string)$item->Value),
                    'nominal' => str_replace(',', '.', (string)$item->Nominal)];
            }
        }

        $xmlstr = file_get_contents(
            str_replace(
                '%date%', $date->format('d/m/Y'), self::URL_METAL_COURSES
            )
        );
        $simpleXml = new \SimpleXMLElement($xmlstr);
        if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
            foreach ($simpleXml->Record as $item) {
                $result[(string)$item['Code']] = [
                    'value'   => str_replace(',', '.', (string)$item->Buy),
                    'nominal' => 1];
            }
        }
        return $result;
    }

    protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    ) {
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
            case CourseEqDate::class:
                $qb->andWhere($this->entityName . '.dateCreate = :dateCreate')
                    ->setParameter('dateCreate', $criterion->getValues());
                break;
        }
    }

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