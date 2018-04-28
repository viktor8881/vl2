<?php
namespace Cron\Controller;

use Base\Service\Date;
use Course\Entity\CacheCourse;
use Course\Entity\MoexCacheCourse;
use Course\Service\MoexCacheCourseManager;
use Course\Service\MoexCacheCourseService;
use Course\Service\MoexManager;
use Course\Service\MoexService;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;

class MoexCacheCourseController extends AbstractActionController
{

    const INIT_DATE = '2017-09-04';
    const COUNT_RUN_AT_TIME = 50;


    const STABLE_TREND = 3;

    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var MoexManager */
    private $courseManager;

    /** @var MoexService */
    private $courseService;

    /** @var MoexCacheCourseManager */
    private $cacheCourseManager;

    /** @var MoexCacheCourseService */
    private $cacheCourseService;


    public function __construct(ExchangeManager $exchangeManager,
        MoexManager $courseManager, MoexService $courseService,
        MoexCacheCourseManager $cacheCourseManager, MoexCacheCourseService $cacheCourseService)
    {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->courseService = $courseService;
        $this->cacheCourseManager = $cacheCourseManager;
        $this->cacheCourseService = $cacheCourseService;
    }

    private function tmpAction()
    {
        $date = new \DateTime(self::INIT_DATE);
        foreach($this->courseManager->fetchAllByDate($date) as $course) {
            foreach (MoexCacheCourseService::listPercent() as $percent) {
                /** @var MoexCacheCourse $newCacheCourse */
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


    private function tmp1Action()
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
                echo 'final';
                exit;
            }
            $this->fillCacheAction( clone $date, true);
            $date->add(new \DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }
        echo 'ok';
        return $this->getResponse();
    }


    public function fillCacheAction(\DateTime $dateNow = null, $hideMess = false)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime();
        }
        if ($this->courseManager->hasByDate($dateNow)) {
            try {
                $exchanges = $this->exchangeManager->fetchAll();
                foreach($this->courseManager->fetchAllByExchangesAndDate($exchanges, $dateNow) as $course) {
                    $this->cacheCourseService->fillingCache($dateNow, $course);
                }
            } catch (\Exception $exception) {
                $this->getResponse()->setStatusCode(500);
                return $this->getResponse();
            }
        } else {
            $this->getResponse()->setStatusCode(412);
        }
        if (!$hideMess) {
            echo 'ok!';
        }
        return $this->getResponse();
    }
}
