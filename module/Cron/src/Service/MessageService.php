<?php

namespace Cron\Service;

use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\MovingAverage;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Base\Entity\CriterionCollection;
use Base\Service\JpGraphService;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Service\CourseManager;
use Exchange\Entity\Exchange;

class MessageService
{
    /** @var TaskOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;
    /** @var TaskPercentAnalysisManager */
    private $taskPercentAnalysisManager;
    /** @var FigureAnalysisManager */
    private $figureAnalysisManager;
    /** @var MovingAverage */
    private $movingAverage;
    /** @var CourseManager */
    private $courseManager;
    /** @var JpGraphService */
    private $graphService;


    /** @var \DateTime */
    private $date;


    public function __construct(TaskOvertimeAnalysisManager $taskOvertimeAnalysisManager,
                                TaskPercentAnalysisManager $taskPercentAnalysisManager,
                                FigureAnalysisManager $figureAnalysisManager,
                                MovingAverage $movingAverage,
                                CourseManager $courseManager,
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
     * @param Exchange $exchange
     * @return int
     */
    public function getStatusCrossByExchange(Exchange $exchange)
    {
        return $this->movingAverage->getStatusCrossByExchangeAndDate($exchange, $this->date);
    }

    /**
     * @param Exchange $exchange
     * @return \Analysis\Entity\TaskPercentAnalysis[]
     */
    public function getListAnalyzesFigureTaskByExchange(Exchange $exchange)
    {
        return $this->getCollectionFigureAnalysisByDate()->listByExchange($exchange);
    }

    /**
     * @param Exchange $exchange
     * @return \Analysis\Entity\TaskOvertimeAnalysis|null
     */
    public function getAnalyzesOvertimeTaskByExchange(Exchange $exchange)
    {
        return $this->getCollectionTaskOvertimeByDate()->getByExchange($exchange);
    }

    /**
     * @param Exchange $exchange
     * @return \Analysis\Entity\TaskPercentAnalysis[]
     */
    public function getAnalyzesPercentTaskByExchange(Exchange $exchange)
    {
        return $this->getCollectionTaskPercentByDate()->listByExchange($exchange);
    }

    public function getSrcGraph(Exchange $exchange)
    {
        $dateEnd = $this->date;
        $dateStart = clone $this->date;
        $dateStart->sub(new \DateInterval('P3M'));

        $criteria = new CriterionCollection();
        $criteria->append(new CriterionExchange($exchange->getId()));
        $criteria->append(new CriterionPeriod([$dateStart, $dateEnd]));

        $courses = $this->courseManager->fetchAllByCriterions($criteria);

        $movingAverage1= $this->movingAverage->listAvgByCourses($courses, 9);
        $movingAverage2= $this->movingAverage->listAvgByCourses($courses, 14);

        $dataBaseGraph = [];
        $dataAvg1 = [];
        $dataAvg2 = [];
        $dataLabels = [];
        $i = 0;
        foreach ($courses as $course) {
            $dataBaseGraph[] = $course->getValue();
            $dataAvg1[] = $movingAverage1[$i];
            $dataAvg2[] = $movingAverage2[$i];
            $dataLabels[] = $course->getDate()->format('d.m');
            $i++;
        }
        $pathImg = $this->graphService->generateGraphByParams($dataBaseGraph, $dataAvg1, $dataAvg2, $dataLabels);
        return $pathImg;
    }


    /**
     * @return \Analysis\Entity\TaskOvertimeAnalysisCollection
     */
    private function getCollectionTaskOvertimeByDate()
    {
        return $this->taskOvertimeAnalysisManager->getCollectionByDate($this->date);
    }

    /**
     * @return \Analysis\Entity\TaskPercentAnalysisCollection
     */
    private function getCollectionTaskPercentByDate()
    {
        return $this->taskPercentAnalysisManager->getCollectionByDate($this->date);
    }

    /**
     * @return \Analysis\Entity\TaskPercentAnalysisCollection
     */
    private function getCollectionFigureAnalysisByDate()
    {
        return $this->figureAnalysisManager->getCollectionByDate($this->date);
    }

}