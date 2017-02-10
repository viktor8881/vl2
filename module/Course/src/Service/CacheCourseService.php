<?php
namespace Course\Service;


use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Course\Entity\Course;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPercent;
use Course\Entity\Criterion\CriterionPeriod;
use Base\Service\AbstractManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

class CacheCourseService
{

    const STABLE_TREND = 3;
    private $listPercents = [0.06, 0.1, 0.2, 0.4, 0.6, 0.8, 1, 1.35, 1.7, 2];

    /**
     * @param \DateTime $date
     * @param array     $listExchangeCode
     */
    public function run(\DateTime $date, Course $course)
    {
        foreach ($this->listPercents as $percent) {
            $cacheCourse = $this->getManager('cacheCourseMetal')->lastByCodePercent($course->getCode(), $percent);
            $arr4Analysis = array($cacheCourse->getLastValue(), $course->getValue());
            if ($cacheCourse->isUpTrend()) {
                $isContinueTrend = Service_GraphAnalysis::isUpTrend($arr4Analysis, $cacheCourse->getPercent());
            }else{
                $isContinueTrend = Service_GraphAnalysis::isDownTrend($arr4Analysis, $cacheCourse->getPercent());
            }
            if ($isContinueTrend or Service_GraphAnalysis::isEqualChannel($arr4Analysis, $cacheCourse->getPercent())) {
                $cacheCourse->setLastValue($course->getValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($date);
                $this->getManager('cacheCourseMetal')->update($cacheCourse);
            }else{
                $typeTrend = $cacheCourse->isUpTrend()?CacheCourseMetal_Model::TREND_DOWN:CacheCourseMetal_Model::TREND_UP;
                $newCacheCourse = $this->getManager('cacheCourseMetal')->createModel();
                $newCacheCourse->setCode($course->getCode())
                    ->setLastValue($course->getValue())
                    ->setTypeTrend($typeTrend)
                    ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($date)
                    ->setPercent($cacheCourse->getPercent());
                $this->getManager('cacheCourseMetal')->insert($newCacheCourse);
            }
            // make analysis
            $metal = $this->getManager('metal')->getByCode($course->getCode());
            $this->technicalAnalysisMetal($metal, $date, $percent);
        }
    }

}