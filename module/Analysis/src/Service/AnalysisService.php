<?php
namespace Analysis\Service;


use Analysis\Entity\FigureAnalysis;
use Analysis\Entity\TaskOvertimeAnalysis;
use Analysis\Entity\TaskPercentAnalysis;
use Base\Service\Math;
use Course\Entity\Course;
use Course\Entity\CourseCollection;
use Course\Service\CacheCourseManager;
use Course\Service\CourseManager;
use Exchange\Entity\Exchange;
use Task\Entity\Task;
use Task\Entity\TaskOvertime;
use Task\Entity\TaskPercent;

class AnalysisService
{

    const STABLE_TREND = 3;

    /** @var CourseManager */
    private $courseManager;
    /** @var FigureAnalysisManager */
    private $figureAnalysisManager;
    /** @var TaskPercentAnalysisManager */
    private $taskPercentAnalysisManager;
    /** @var TaskOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;
    /** @var CacheCourseManager */
    private $cacheCourseManager;

    /**
     * AnalysisService constructor.
     *
     * @param CourseManager               $courseManager
     * @param FigureAnalysisManager       $figureAnalysisManager
     * @param TaskPercentAnalysisManager  $taskPercentAnalysisManager
     * @param TaskOvertimeAnalysisManager $taskOvertimeAnalysisManager
     * @param CacheCourseManager          $cacheCourseManager
     */
    public function __construct(CourseManager $courseManager,
        FigureAnalysisManager $figureAnalysisManager,
        TaskPercentAnalysisManager $taskPercentAnalysisManager,
        TaskOvertimeAnalysisManager $taskOvertimeAnalysisManager,
        CacheCourseManager $cacheCourseManager
    ) {
        $this->courseManager = $courseManager;
        $this->figureAnalysisManager = $figureAnalysisManager;
        $this->taskPercentAnalysisManager = $taskPercentAnalysisManager;
        $this->taskOvertimeAnalysisManager = $taskOvertimeAnalysisManager;
        $this->cacheCourseManager = $cacheCourseManager;
    }


    /**
     * @param Task      $task
     * @param \DateTime $date
     * @return int
     */
    public function analysisByTask(Task $task, \DateTime $date)
    {
        $count = 0;
        if ($task instanceof TaskPercent) {
            $count += $this->runPercentByTask($task, $date);
        } elseif ($task  instanceof TaskOvertime) {
            $count += $this->runOvertimeByTask($task, $date);
        } else {
            throw new \RuntimeException('Unknow type task');
        }
        return $count;
    }

    /**
     * @param TaskPercent $task
     * @param \DateTime   $date
     *
     * @return int
     */
    public function runPercentByTask(TaskPercent $task, \DateTime $date)
    {
        $countRec = 0;
        $dateLater = clone $date;
        $dateLater->sub(new \DateInterval('P' . $task->getPeriod() . 'D'));
        foreach ($task->getExchanges() as $exchange) {
            $courses = $this->courseManager->fetchAllByExchangeAndPeriod($exchange, $dateLater, $date);
            if (!$courses) {
                continue;
            }
            /** @var Course $courseFirst */
            $courseFirst = reset($courses);
            /** @var Course $courseLast */
            $courseLast = end($courses);
            if ($this->isValidTaskPercent($task, $courseFirst->getValue(), $courseLast->getValue())) {
                /** @var  $analysis TaskPercentAnalysis */
                $analysis = $this->taskPercentAnalysisManager->createEntity();
                $analysis->setExchange($exchange)
                    ->setCourses([$courseFirst, $courseLast])
                    ->setPeriod($task->getPeriod())
                    ->setCreated($date)
                    ->setPercent($task->getPercent());
                $this->figureAnalysisManager->insert($analysis);
                $countRec++;
            }
        }
        return $countRec;
    }

    /**
     * @param TaskPercent $task
     * @param             $firstValue
     * @param             $lastValue
     * @return bool
     */
    protected function isValidTaskPercent(TaskPercent $task, $firstValue, $lastValue) {
        $percent = $task->getPercent();
        $upValue = $firstValue * (1 + $percent / 100);
        $downValue = $firstValue * (1 - $percent / 100);
        switch ($task->getMode()) {
            case TaskPercent::MODE_ONLY_UP:
                if (Math::round($upValue, 6) < Math::round($lastValue, 6)) {
                    return true;
                }
                break;
            case TaskPercent::MODE_ONLY_DOWN:
                if (Math::round($downValue, 6) > Math::round($lastValue, 6)) {
                    return true;
                }
                break;
            case TaskPercent::MODE_UP_DOWN:
                if (Math::round($upValue, 6) < Math::round($lastValue, 6) or
                    Math::round($downValue, 6) > Math::round($lastValue, 6)
                ) {
                    return true;
                }
                break;
        }
        return false;
    }


    /**
     * @param TaskOvertime $task
     * @param \DateTime    $date
     *
     * @return int
     */
    public function runOvertimeByTask(TaskOvertime $task, \DateTime $date)
    {
        $countRec = 0;
        foreach ($task->getExchanges() as $exchange) {
            /** @var CourseCollection $courses */
            $courses = $this->courseManager->getCollectionByExchangeAndLsDate($exchange, $date);
            $courses = $courses->listExchangeUpOrDown();
            $listValue = [];
            /** @var Course[] $courses */
            foreach ($courses as $course) {
                $listValue[] = $course->getValue();
            }
            if ($this->isValidTaskOvertime($task, $listValue)) {
                /** @var  $analysis TaskOvertimeAnalysis */
                $analysis = $this->taskOvertimeAnalysisManager->createEntity();
                $analysis->setExchange($exchange)
                    ->setCourses($courses)
                    ->setPeriod($task->getPeriod())
                    ->setCreated($date);
                $this->figureAnalysisManager->insert($analysis);
                $countRec++;
            }
        }
        return $countRec;
    }

    /**
     * @param TaskOvertime $task
     * @param array        $values
     *
     * @return bool
     */
    protected function isValidTaskOvertime(TaskOvertime $task, array $values = [])
    {
        if (count($values) >= $task->getPeriod()) {
            $firstValue = reset($values);
            $lastValue = end($values);
            switch ($task->getMode()) {
                case TaskOverTime::MODE_ONLY_UP:
                    if (Math::round($firstValue, 6) < Math::round($lastValue, 6)) {
                        return true;
                    }
                    break;
                case TaskOverTime::MODE_ONLY_DOWN:
                    if (Math::round($firstValue, 6) > Math::round($lastValue, 6)) {
                        return true;
                    }
                    break;
                case TaskOverTime::MODE_UP_DOWN:
                    if (Math::round($firstValue, 6) != Math::round($lastValue, 6)) {
                        return true;
                    }
                    break;
            }
        }
        return false;
    }

    /**
     * @param Exchange  $exchange
     * @param \DateTime $date
     * @param float $percent
     */
    public function technicalAnalysisByExchange(Exchange $exchange, \DateTime $date, $percent)
    {
        // фигура W и M.
        $cacheCourses = $this->cacheCourseManager->fetch5ByExchangeAndPercent($exchange, $percent);
        if ($cacheCourses && $cacheCourses->countFirstData() >= self::STABLE_TREND && $cacheCourses->lastNullOperation()) {
            if ( $cacheCourses->firstIsUpTrend() && TechnicalAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                /** @var $analysis FigureAnalysis */
                $analysis = $this->figureAnalysisManager->createEntity();
                $analysis->setExchange($exchange)
                    ->setFigure(FigureAnalysis::FIGURE_DOUBLE_BOTTOM)
                    ->setCacheCourses($cacheCourses->getList())
                    ->setCreated($date);
                $this->figureAnalysisManager->insert($analysis);
            }elseif ($cacheCourses->firstIsDownTrend() && TechnicalAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                /** @var FigureAnalysis $analysis */
                $analysis = $this->figureAnalysisManager->createEntity();
                $analysis->setExchange($exchange)
                    ->setFigure(FigureAnalysis::FIGURE_DOUBLE_TOP)
                    ->setCacheCourses($cacheCourses->getList())
                    ->setCreated($date);
                $this->figureAnalysisManager->insert($analysis);
            }
        }
        // =============================================================
        // фигура тройное дно, ReverseS&H, тройные вершины S&H
        $cacheCourses = $this->cacheCourseManager->fetch7ByExchangeAndPercent($exchange, $percent);
        if ($cacheCourses
            && $cacheCourses->countFirstData() >= self::STABLE_TREND
            && $cacheCourses->lastNullOperation() ) {

            if ($cacheCourses->firstIsUpTrend()) {
                if (TechnicalAnalysis::isTripleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // пишем что образовалась фигура
                    /** @var FigureAnalysis $analysis */
                    $analysis = $this->figureAnalysisManager->createEntity();
                    $analysis->setExchange($exchange)
                        ->setFigure(FigureAnalysis::FIGURE_TRIPLE_BOTTOM)
                        ->setCacheCourses($cacheCourses->getList())
                        ->setCreated($date);
                    $this->figureAnalysisManager->insert($analysis);
                }
                if (TechnicalAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                    // пишем что образовалась фигура
                    /** @var FigureAnalysis $analysis */
                    $analysis = $this->figureAnalysisManager->createEntity();
                    $analysis->setExchange($exchange)
                        ->setFigure(FigureAnalysis::FIGURE_RESERVE_HEADS_HOULDERS)
                        ->setCacheCourses($cacheCourses->getList())
                        ->setCreated($date);
                    $this->figureAnalysisManager->insert($analysis);
                }
            }elseif($cacheCourses->firstIsDownTrend()) {
                if (TechnicalAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // пишем что образовалась фигура
                    /** @var FigureAnalysis $analysis */
                    $analysis = $this->figureAnalysisManager->createEntity();
                    $analysis->setExchange($exchange)
                        ->setFigure(FigureAnalysis::FIGURE_TRIPLE_TOP)
                        ->setCacheCourses($cacheCourses->getList())
                        ->setCreated($date);
                    $this->figureAnalysisManager->insert($analysis);
                }
                if (TechnicalAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                    // пишем что образовалась фигура
                    /** @var FigureAnalysis $analysis */
                    $analysis = $this->figureAnalysisManager->createEntity();
                    $analysis->setExchange($exchange)
                        ->setFigure(FigureAnalysis::FIGURE_HEADS_HOULDERS)
                        ->setCacheCourses($cacheCourses->getList())
                        ->setCreated($date);
                    $this->figureAnalysisManager->insert($analysis);
                }
            }

        }
    }


}
