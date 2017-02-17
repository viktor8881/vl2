<?php
namespace Course\Service;

use Course\Entity\CacheCourse;
use Course\Entity\Course;
use Analysis\Service\TechnicalAnalysis;

class CacheCourseService
{

    const STABLE_TREND = 3;
    private $listPercents = [0.06, 0.1, 0.2, 0.4, 0.6, 0.8, 1, 1.35, 1.7, 2];


    /** @var CacheCourseManager */
    private $cacheCourseManager;


    /**
     * @param CacheCourseManager $cacheCourseManager
     */
    public function __construct(CacheCourseManager $cacheCourseManager)
    {
        $this->cacheCourseManager = $cacheCourseManager;
    }

    /**
     * @param \DateTime $date
     * @param array     $listExchangeCode
     */
    public function fillingCache(\DateTime $date, Course $course)
    {
        foreach ($this->listPercents as $percent) {
            /** @var CacheCourse $cacheCourse */
            $cacheCourse = $this->cacheCourseManager->lastByExchangeAndPercent($course->getExchange(), $percent);
            $arr4Analysis = array($cacheCourse->getLastValue(), $course->getValue());
            if ($cacheCourse->isUpTrend()) {
                $isContinueTrend = TechnicalAnalysis::isUpTrend($arr4Analysis, $cacheCourse->getPercent());
            }else{
                $isContinueTrend = TechnicalAnalysis::isDownTrend($arr4Analysis, $cacheCourse->getPercent());
            }
            if ($isContinueTrend or TechnicalAnalysis::isEqualChannel($arr4Analysis, $cacheCourse->getPercent())) {
                $cacheCourse->setLastValue($course->getValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($date);
                $this->cacheCourseManager->update($cacheCourse);
            }else{
                $typeTrend = $cacheCourse->isUpTrend() ? CacheCourse::TREND_DOWN : CacheCourse::TREND_UP;
                /** @var CacheCourse $newCacheCourse */
                $newCacheCourse = $this->cacheCourseManager->createEntity();
                $newCacheCourse->setExchange($course->getExchange())
                    ->setLastValue($course->getValue())
                    ->setTypeTrend($typeTrend)
                    ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($date)
                    ->setPercent($cacheCourse->getPercent());
                $this->cacheCourseManager->insert($newCacheCourse);
            }
        }
    }

}