<?php
namespace Course\Service;

use Analysis\Service\TechnicalAnalysis;
use Course\Entity\CacheCourse;
use Course\Entity\Course;

class CacheCourseService
{

    const STABLE_TREND = 3;
    private static $listPercent = [0.06, 0.1, 0.2, 0.4, 0.6, 0.8, 1, 1.35, 1.7, 2];


    /** @var CacheCourseManager */
    private $cacheCourseManager;


    /**
     * @return Float[]
     */
    public static function listPercent()
    {
        return self::$listPercent;
    }

    /**
     * @param CacheCourseManager $cacheCourseManager
     */
    public function __construct(CacheCourseManager $cacheCourseManager)
    {
        $this->cacheCourseManager = $cacheCourseManager;
    }

    /**
     * @param \DateTime $date
     * @param Course $course
     */
    public function fillingCache(\DateTime $date, Course $course)
    {
        foreach (self::listPercent() as $percent) {
            /** @var CacheCourse $cacheCourse */
            $cacheCourse = $this->cacheCourseManager->lastByExchangeAndPercent($course->getExchange(), $percent);
            if (!$cacheCourse) {
                continue;
            }
//            pr($cacheCourse); exit;
            $arr4Analysis = [$cacheCourse->getLastValue(), $course->getValue()];
            if ($cacheCourse->isUpTrend()) {
                $isContinueTrend = TechnicalAnalysis::isUpTrend($arr4Analysis, $cacheCourse->getPercent());
            }else{
                $isContinueTrend = TechnicalAnalysis::isDownTrend($arr4Analysis, $cacheCourse->getPercent());
            }
            if ($isContinueTrend or TechnicalAnalysis::isEqualChannel($arr4Analysis, $cacheCourse->getPercent())) {
                $cacheCourse->setLastValue($course->getValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($date);
//                pr($cacheCourse); exit;
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