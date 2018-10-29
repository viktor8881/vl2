<?php
namespace Course\Service;

use Analysis\Service\TechnicalAnalysis;
use Course\Entity\CacheCourse;
use Course\Entity\Moex;
use Course\Entity\MoexCacheCourse;

class MoexCacheCourseService
{

    const STABLE_TREND = 3;
    private static $listPercent = [0, 0.05, 0.1];


    /** @var MoexCacheCourseManager */
    private $cacheCourseManager;


    /**
     * @return Float[]
     */
    public static function listPercent()
    {
        return self::$listPercent;
    }

    /**
     * @param MoexCacheCourseManager $cacheCourseManager
     */
    public function __construct(MoexCacheCourseManager $cacheCourseManager)
    {
        $this->cacheCourseManager = $cacheCourseManager;
    }

    /**
     * @param Moex $course
     */
    public function fillingCache(Moex $course)
    {
        foreach (self::listPercent() as $percent) {
            /** @var MoexCacheCourse $cacheCourse */
            $cacheCourse = $this->cacheCourseManager->lastByExchangeAndPercent($course->getExchange(), $percent);
            if (!$cacheCourse) {
                $newCacheCourse = $this->cacheCourseManager->createEntity();
                $newCacheCourse->setExchange($course->getExchange())
                    ->setLastValue($course->getValue())
                    ->setTypeTrend(CacheCourse::TREND_DOWN)
                    ->addDataValueByCourse($course)
                    ->setLastDate($course->getTradeDateTime())
                    ->setPercent($percent);
                $this->cacheCourseManager->insert($newCacheCourse);
                continue;
            }
            $arr4Analysis = [$cacheCourse->getLastValue(), $course->getValue()];
            if ($cacheCourse->isUpTrend()) {
                $isContinueTrend = TechnicalAnalysis::isUpTrend($arr4Analysis, $cacheCourse->getPercent());
            } else {
                $isContinueTrend = TechnicalAnalysis::isDownTrend($arr4Analysis, $cacheCourse->getPercent());
            }

            if ($isContinueTrend or TechnicalAnalysis::isEqualChannel($arr4Analysis, $cacheCourse->getPercent())) {
                $cacheCourse->setLastValue($course->getValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($course->getTradeDateTime());
                $this->cacheCourseManager->update($cacheCourse);
            } else {
                $typeTrend = $cacheCourse->isUpTrend() ? CacheCourse::TREND_DOWN : CacheCourse::TREND_UP;
                /** @var CacheCourse $newCacheCourse */
                $newCacheCourse = $this->cacheCourseManager->createEntity();
                $newCacheCourse->setExchange($course->getExchange())
                    ->setLastValue($course->getValue())
                    ->setTypeTrend($typeTrend)
                    ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                    ->addDataValueByCourse($course)
                    ->setLastDate($course->getTradeDateTime())
                    ->setPercent($cacheCourse->getPercent());
                $this->cacheCourseManager->insert($newCacheCourse);
            }
        }
    }

}