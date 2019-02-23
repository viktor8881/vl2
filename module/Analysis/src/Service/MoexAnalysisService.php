<?php
namespace Analysis\Service;


use Analysis\Entity\Criterion\CriterionDateCreated;
use Analysis\Entity\Criterion\CriterionExchange;
use Analysis\Entity\FigureAnalysis;
use Analysis\Entity\MoexFigureAnalysis;
use Analysis\Entity\TaskOvertimeAnalysis;
use Analysis\Entity\TaskPercentAnalysis;
use Base\Entity\CriterionCollection;
use Base\Service\Math;
use Course\Entity\Course;
use Course\Entity\Moex;
use Course\Entity\MoexCollection;
use Course\Service\MoexCacheCourseManager;
use Course\Service\MoexManager;
use Exchange\Entity\Exchange;
use Task\Entity\Task;
use Task\Entity\TaskOvertime;
use Task\Entity\TaskPercent;
use Zend\Cache\Storage\StorageInterface;

class MoexAnalysisService
{

    const STABLE_TREND = 3;

    /** @var MoexManager */
    private $courseManager;
    /** @var MoexFigureAnalysisManager */
    private $figureAnalysisManager;
    /** @var MoexPercentAnalysisManager */
    private $taskPercentAnalysisManager;
    /** @var MoexOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;
    /** @var MoexCacheCourseManager */
    private $cacheCourseManager;
    /** @var StorageInterface*/
    private $cacheStorage;

    /**
     * AnalysisService constructor.
     *
     * @param MoexManager               $courseManager
     * @param MoexFigureAnalysisManager       $figureAnalysisManager
     * @param MoexPercentAnalysisManager  $taskPercentAnalysisManager
     * @param MoexOvertimeAnalysisManager $taskOvertimeAnalysisManager
     * @param MoexCacheCourseManager          $cacheCourseManager
     */
    public function __construct(MoexManager $courseManager,
        MoexFigureAnalysisManager $figureAnalysisManager,
        MoexPercentAnalysisManager $taskPercentAnalysisManager,
        MoexOvertimeAnalysisManager $taskOvertimeAnalysisManager,
        MoexCacheCourseManager $cacheCourseManager,
        StorageInterface $cacheStorage
    ) {
        $this->courseManager = $courseManager;
        $this->figureAnalysisManager = $figureAnalysisManager;
        $this->taskPercentAnalysisManager = $taskPercentAnalysisManager;
        $this->taskOvertimeAnalysisManager = $taskOvertimeAnalysisManager;
        $this->cacheCourseManager = $cacheCourseManager;
        $this->cacheStorage = $cacheStorage;
    }


    /**
     * @param Task      $task
     * @param \DateTime $date
     * @param Exchange $exchange
     * @return int
     */
    public function analysisByTask(Task $task, \DateTime $date, Exchange $exchange)
    {
        $count = 0;
        if ($task instanceof TaskPercent) {
            $count += $this->runPercentByTask($task, $date, $exchange);
        } elseif ($task  instanceof TaskOvertime) {
            $count += $this->runOvertimeByTask($task, $date, $exchange);
        }
        return $count;
    }

    /**
     * @param TaskPercent $task
     * @param \DateTime   $date
     * @param Exchange $exchange
     * @return int
     */
    public function runPercentByTask(TaskPercent $task, \DateTime $date, Exchange $exchange)
    {
        $countRec = 0;
        if (!$task->hasExchangeId($exchange->getId())) {
            return $countRec;
        }
        $dateLater = clone $date;
        $dateLater->sub(new \DateInterval('P' . $task->getPeriod() . 'D'));

        $courses = $this->courseManager->fetchAllByExchangesAndPeriod([$exchange], $dateLater, $date);
        if (!$courses) {
            return $countRec;
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
            $this->taskPercentAnalysisManager->insert($analysis);
            $countRec++;
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
     * @param Exchange $exchange
     * @return int
     */
    public function runOvertimeByTask(TaskOvertime $task, \DateTime $date, Exchange $exchange)
    {
        $countRec = 0;
        if (!$task->hasExchangeId($exchange->getId())) {
            return $countRec;
        }
        /** @var MoexCollection $courses */
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
            $this->taskOvertimeAnalysisManager->insert($analysis);
            $countRec++;
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
            if ( $cacheCourses->firstIsUpTrend() && MoexTechnicalAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                /** @var $analysis FigureAnalysis */
                $analysis = $this->figureAnalysisManager->createEntity();
                $analysis->setExchange($exchange)
                    ->setFigure(FigureAnalysis::FIGURE_DOUBLE_BOTTOM)
                    ->setCacheCourses($cacheCourses->getList())
                    ->setCreated($date);
                $this->figureAnalysisManager->insert($analysis);
            }elseif ($cacheCourses->firstIsDownTrend() && MoexTechnicalAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
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
                if (MoexTechnicalAnalysis::isTripleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // пишем что образовалась фигура
                    /** @var FigureAnalysis $analysis */
                    $analysis = $this->figureAnalysisManager->createEntity();
                    $analysis->setExchange($exchange)
                        ->setFigure(FigureAnalysis::FIGURE_TRIPLE_BOTTOM)
                        ->setCacheCourses($cacheCourses->getList())
                        ->setCreated($date);
                    $this->figureAnalysisManager->insert($analysis);
                }
                if (MoexTechnicalAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
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
                if (MoexTechnicalAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // пишем что образовалась фигура
                    /** @var FigureAnalysis $analysis */
                    $analysis = $this->figureAnalysisManager->createEntity();
                    $analysis->setExchange($exchange)
                        ->setFigure(FigureAnalysis::FIGURE_TRIPLE_TOP)
                        ->setCacheCourses($cacheCourses->getList())
                        ->setCreated($date);
                    $this->figureAnalysisManager->insert($analysis);
                }
                if (MoexTechnicalAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
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

    /**
     * @param Exchange{} $exchanges
     * @return array
     */
    public function listOrderWeight($exchanges = [], $clearCache = false)
    {
        $dateNow = new \DateTime();
        /** @var $exchange Exchange */
        foreach ($exchanges as $exchange) {
            $exchangeId = $exchange->getId();
            $keyCacheStorage = $exchangeId . 'listOrderWeight';
            $result[$exchangeId] = $this->cacheStorage->getItem($keyCacheStorage, $success);
            if ($clearCache || !$success) {
                $result[$exchangeId] = [
                    'overtime' => [],
                    'percent' => [],
                    'figure' => [],
                    'dateTrade' => '-',
                    'weight' => 0
                ];
                $moex = $this->courseManager->lastByExchangeId($exchangeId);
                if ($moex && $dateNow->diff($moex->getDate(), true)->format('%a') <= 10) {
                    $result[$exchangeId]['dateTrade'] = $moex->getDateFormatDMY();
                    $criterions = new CriterionCollection();
                    $criterions->append(new CriterionDateCreated($moex->getDate()));
                    $criterions->append(new CriterionExchange($moex->getExchange()));
                    foreach ($this->taskOvertimeAnalysisManager->fetchAllByCriterions($criterions) as $entity) {
                        $result[$exchangeId]['overtime'][] = $entity;
                        if ($entity->isQuotesGrowth()) {
                            $result[$exchangeId]['weight'] += 1 * $entity->countData();
                        } else {
                            $result[$exchangeId]['weight'] -= 1 * $entity->countData();
                        }
                    }
                    foreach ($this->taskPercentAnalysisManager->fetchAllByCriterions($criterions) as $entity) {
                        $result[$exchangeId]['percent'][] = $entity;
                        if ($entity->isQuotesGrowth()) {
                            $result[$exchangeId]['weight'] += 1 * $entity->getDiffPercent();
                        } else {
                            $result[$exchangeId]['weight'] -= 1 * $entity->getDiffPercent();
                        }
                    }
                    foreach ($this->figureAnalysisManager->fetchAllByCriterions($criterions) as $entity) {
                        $result[$exchangeId]['figure'][] = $entity;
                        switch ($entity->getFigure()) {
                            case MoexFigureAnalysis::FIGURE_DOUBLE_BOTTOM :
                                $result[$exchangeId]['weight'] += 1 * 1;
                                break;
                            case MoexFigureAnalysis::FIGURE_TRIPLE_BOTTOM :
                                $result[$exchangeId]['weight'] += 1 * 2;
                                break;
                            case MoexFigureAnalysis::FIGURE_RESERVE_HEADS_HOULDERS :
                                $result[$exchangeId]['weight'] += 1 * 3;
                                break;
                            case MoexFigureAnalysis::FIGURE_DOUBLE_TOP :
                                $result[$exchangeId]['weight'] -= 1 * 1;
                                break;
                            case MoexFigureAnalysis::FIGURE_TRIPLE_TOP :
                                $result[$exchangeId]['weight'] -= 1 * 2;
                                break;
                            case MoexFigureAnalysis::FIGURE_HEADS_HOULDERS :
                                $result[$exchangeId]['weight'] -= 1 * 3;
                                break;
                        }
                    }
                }
                $this->cacheStorage->setItem($keyCacheStorage, $result[$exchangeId]);
            }
            $result[$exchangeId]['exchange' ] = $exchange;
        }
        usort($result, [$this, 'order']);
        return $result;
    }

    private function order(array $a, array $b)
    {
        list($revertHeadSholdersA, $tripleBottomA, $doubleBottomA) = AnalysisOrder::findFigure($a['figure']);
        list($revertHeadSholdersB, $tripleBottomB, $doubleBottomB) = AnalysisOrder::findFigure($b['figure']);
        
        if ($revertHeadSholdersA && $revertHeadSholdersB) {
            return $this->cmp($revertHeadSholdersA->getFirstDate(), $revertHeadSholdersB->getFirstDate());
        } elseif ($revertHeadSholdersA && !$revertHeadSholdersB) {
            return -1;
        } elseif (!$revertHeadSholdersA && $revertHeadSholdersB) {
            return 1;
        }

        if ($tripleBottomA && $tripleBottomB) {
            return $this->cmp($tripleBottomA->getFirstDate(), $tripleBottomB->getFirstDate());
        } elseif ($tripleBottomA && !$tripleBottomB) {
            return -1;
        } elseif (!$tripleBottomA && $tripleBottomB) {
            return 1;
        }

        if ($doubleBottomA && $doubleBottomB) {
            return $this->cmp($doubleBottomA->getFirstDate(), $doubleBottomB->getFirstDate());
        } elseif ($doubleBottomA && !$doubleBottomB) {
            return -1;
        } elseif (!$doubleBottomA && $doubleBottomB) {
            return 1;
        }

        return $this->cmp($a['weight'], $b['weight']);
    }


    private function cmp($resultA, $resultB)
    {
        if ($resultA == $resultB) {
            return 0;
        }
        return ($resultA < $resultB) ? 1 : -1;
    }

}
