<?php

namespace Cron\Service;

use Analysis\Entity\MoexFigureAnalysis;
use Analysis\Entity\MoexOvertimeAnalysis;
use Analysis\Entity\MoexPercentAnalysis;
use Analysis\Service\MoexFigureAnalysisManager;
use Analysis\Service\MoexOvertimeAnalysisManager;
use Analysis\Service\MoexPercentAnalysisManager;
use Analysis\Service\MovingAverage;
use Base\Entity\CriterionCollection;
use Base\Service\JpGraphService;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Entity\Moex;
use Course\Service\MoexManager;
use Exchange\Entity\Exchange;

class MoexMessageService implements MessageInterface
{

    const IMG_NO_DATA = '/img/jpgraph/no-data.png';

    /** @var MoexOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;

    /** @var MoexPercentAnalysisManager */
    private $taskPercentAnalysisManager;

    /** @var MoexFigureAnalysisManager */
    private $figureAnalysisManager;

    /** @var MovingAverage */
    private $movingAverage;

    /** @var MoexManager */
    private $courseManager;

    /** @var JpGraphService */
    private $graphService;

    /** @var \DateTime */
    private $date;

    /** @var Exchange */
    private $exchange;


    public function __construct(MoexOvertimeAnalysisManager $taskOvertimeAnalysisManager,
                                MoexPercentAnalysisManager $taskPercentAnalysisManager,
                                MoexFigureAnalysisManager $figureAnalysisManager,
                                MovingAverage $movingAverage,
                                MoexManager $courseManager,
                                JpGraphService $graphService)
    {
        $this->taskOvertimeAnalysisManager = $taskOvertimeAnalysisManager;
        $this->taskPercentAnalysisManager = $taskPercentAnalysisManager;
        $this->figureAnalysisManager = $figureAnalysisManager;
        $this->movingAverage = $movingAverage;
        $this->courseManager = $courseManager;
        $this->graphService = $graphService;
        $this->date = new \DateTime();
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Exchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @param Exchange $exchange
     *
     * @return MoexMessageService
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        $subject = 'MOEX: ';
        $exchange = $this->getExchange();
        if ($exchange) {
            $subject .= $exchange->getName();
        } else {
            $subject .= 'no_subject';
        }
        return $subject;
    }

    /**
     * @return Exchange[]
     */
    public function getListExchange()
    {
        return array_merge(
            $this->getCollectionTaskOvertimeByDate()->listExchange(),
            $this->getCollectionTaskPercentByDate()->listExchange(),
            $this->getCollectionFigureAnalysisByDate()->listExchange()
        );
    }

    /**
     * @return int
     */
    public function getStatusCross()
    {
        return $this->movingAverage->getStatusCrossByExchangeAndDate($this->exchange, $this->date);
    }

    /**
     * @return MoexFigureAnalysis[]
     */
    public function getListAnalyzesFigureTask()
    {
        return $this->getCollectionFigureAnalysisByDate()->listByExchange($this->exchange);
    }

    /**
     * @return MoexOvertimeAnalysis | null
     */
    public function getAnalyzesOvertimeTask()
    {
        return $this->getCollectionTaskOvertimeByDate()->getByExchange($this->exchange);
    }

    /**
     * @return MoexPercentAnalysis[]
     */
    public function getAnalyzesPercentTask()
    {
        return $this->getCollectionTaskPercentByDate()->listByExchange($this->exchange);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getSrcGraph()
    {
        $exchange = $this->exchange;
        $dateEnd = $this->date;
        $dateStart = clone $this->date;
        $dateStart->sub(new \DateInterval('P3M'));

        $criteria = new CriterionCollection();
        $criteria->append(new CriterionExchange($exchange->getId()));
        $criteria->append(new CriterionPeriod([$dateStart, $dateEnd]));

        $courses = $this->courseManager->fetchAllByCriterionsOnUniqDate($criteria);

        if (count($courses) >= 50) {
            $movingAverage1= $this->movingAverage->listAvgByCourses($courses, 9);
            $movingAverage2= $this->movingAverage->listAvgByCourses($courses, 14);

            $dataBaseGraph = [];
            $dataAvg1 = [];
            $dataAvg2 = [];
            $dataLabels = [];
            $i = 0;
            /** @var $course Moex */
            foreach ($courses as $course) {
                $dataBaseGraph[] = $course->getValue();
                $dataAvg1[] = $movingAverage1[$i];
                $dataAvg2[] = $movingAverage2[$i];
                $i++;
                $dataLabels[] = $course->getDate()->format('U');
            }
            $pathImg = $this->graphService->generateGraphByParams($dataBaseGraph, $dataAvg1, $dataAvg2, $dataLabels);
        } else {
            $pathImg = self::IMG_NO_DATA;
        }
        return $pathImg;
    }


    /**
     * @return \Analysis\Entity\MoexOvertimeAnalysisCollection
     */
    private function getCollectionTaskOvertimeByDate()
    {
        return $this->taskOvertimeAnalysisManager->getCollectionByDate($this->date);
    }

    /**
     * @return \Analysis\Entity\MoexPercentAnalysisCollection
     */
    private function getCollectionTaskPercentByDate()
    {
        return $this->taskPercentAnalysisManager->getCollectionByDate($this->date);
    }

    /**
     * @return \Analysis\Entity\MoexFigureAnalysisCollection
     */
    private function getCollectionFigureAnalysisByDate()
    {
        return $this->figureAnalysisManager->getCollectionByDate($this->date);
    }

}