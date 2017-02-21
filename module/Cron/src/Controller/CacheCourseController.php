<?php
namespace Cron\Controller;

use Base\Service\Date;
use Course\Entity\CacheCourse;
use Course\Service\CacheCourseManager;
use Course\Service\CacheCourseService;
use Course\Service\CourseManager;
use Course\Service\CourseService;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;

class CacheCourseController extends AbstractActionController
{

    const INIT_DATE = '07.12.2016';
    const COUNT_RUN_AT_TIME = 100;


    const STABLE_TREND = 3;
    private $listPercents = [0.06, 0.1, 0.2, 0.4, 0.6, 0.8, 1, 1.35, 1.7, 2];

    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var CourseManager */
    private $courseManager;

    /** @var CourseService */
    private $courseService;

    /** @var CacheCourseManager */
    private $cacheCourseManager;

    /** @var CacheCourseService */
    private $cacheCourseService;


    public function __construct(ExchangeManager $exchangeManager,
        CourseManager $courseManager, CourseService $courseService,
        CacheCourseManager $cacheCourseManager, CacheCourseService $cacheCourseService)
    {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->courseService = $courseService;
        $this->cacheCourseManager = $cacheCourseManager;
        $this->cacheCourseService = $cacheCourseService;
    }

    public function tmpAction()
    {
        $date = new \DateTime(self::INIT_DATE);
        foreach($this->courseManager->fetchAllByDate($date) as $course) {
            foreach ($this->listPercents as $percent) {
                /** @var CacheCourse $newCacheCourse */
                $newCacheCourse = $this->cacheCourseManager->createEntity();
                $newCacheCourse->setExchange($course->getExchange())
                    ->setLastValue($course->getValue())
                    ->setTypeTrend(CacheCourse::TREND_UP)
                    ->addDataValueByCourse($course)
                    ->setLastDate($date)
                    ->setPercent($percent);
                $this->cacheCourseManager->insert($newCacheCourse);
            }
        }
        echo 'ok';
        return $this->getResponse();
    }

    public function tmp1Action()
    {
        $tmpDir = 'data/tmp/';
        $dateNow = new Date();
        $fileName = $tmpDir . 'tmp.tmp';
        if (!file_exists($fileName)) {
            exit;
        }
        $i = 0;
        $flag = true;
        while ($flag) {
            if (++$i > self::COUNT_RUN_AT_TIME) {
                $flag = false;
                break;
            }
            // находим дату
            $date = new Date(file_get_contents($fileName));
            if ($date->compareDate($dateNow) == 1) {
                rename($fileName, $tmpDir . '_tmp.tmp');
                $flag = false;
                echo 'exit';
                exit;
            }
            //===================================================================
            pr($date->format('d.m.Y'));
            $this->fillCacheAction( clone $date, true);
            pr($date->format('d.m.Y'));
            pr("==============================");
            //===================================================================
            $date->add(new \DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }
        echo 'ok';
        return $this->getResponse();
    }

    public function fillCacheAction(\DateTime $dateNow = null, $hideMess = false)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime('14.02.2017');
        }
        if ($this->courseManager->hasByDate($dateNow)) {
            try {
                $listExchange = $this->exchangeManager->fetchAllMetal();
                foreach($this->courseManager->fetchAllByListIdAndDate($listExchange, $dateNow) as $cacheCourse)
                {
                    $this->cacheCourseService->fillingCache($dateNow, $cacheCourse);
                }
            } catch (\Exception $exception) {
                pr($exception); exit;
                $this->getResponse()->setStatusCode(500);
                return;
            }
        }
        if (!$hideMess) {
            echo 'ok!';
        }
        return $this->getResponse();
    }
}
