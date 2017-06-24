<?php
namespace Cron\Controller;

use Account\Service\AccountManager;
use Analysis\Service\TechnicalAnalysis;
use Base\Service\Date;
use Base\Service\Math;
use Course\Entity\Course;
use Course\Entity\CourseCollection;
use Course\Service\CacheCourseManager;
use Course\Service\CourseManager;
use Cron\Service\ServiceExp1;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Investments\Entity\Investments;
use Investments\Service\InvestmentsManager;
use Zend\Mvc\Controller\AbstractActionController;

class Exp1Controller extends AbstractActionController
{

    const SUM_INVESTMENT = 10000;

    const COUNT_RUN_AT_TIME = 300;
    const STABLE_TREND = 5;


    const TYPE_OPERATION_NULL = null;
    const TYPE_OPERATION_BUY = 'buy';
    const TYPE_OPERATION_SELL = 'sell';


    /** @var ServiceExp1 */
    private $serviceExr1;
    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var CourseManager */
    private $courseManager;
    /** @var CacheCourseManager */
    private $cacheCourseManager;
    /** @var InvestmentsManager */
    private $investManager;
    /** @var AccountManager */
    private $accountManager;
    /** @var string */
    private $tmpDir;

    public function __construct(ServiceExp1 $serviceExr1,
                                ExchangeManager $exchangeManager,
                                CourseManager $courseManager,
                                CacheCourseManager $cacheCourseManager,
                                InvestmentsManager $investManager,
                                AccountManager $accountManager, $tmpDir
    ) {
        $this->serviceExr1 = $serviceExr1;
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->cacheCourseManager = $cacheCourseManager;
        $this->investManager = $investManager;
        $this->accountManager = $accountManager;
        $this->tmpDir = $tmpDir;
    }

    public function indexAction()
    {
        $dateNow = new Date();
        $fileName = $this->tmpDir . 'tmp.tmp';
        if (!file_exists($fileName)) {
            exit;
        }
        $exchanges = $this->exchangeManager->fetchAllMetal();
        $i = 0;
        while (true) {
            if (++$i > self::COUNT_RUN_AT_TIME) {
                break; // exit from while
            }
            // находим дату
            $date = new Date(file_get_contents($fileName));
            if ($date->compareDate($dateNow) == 1) {
                rename($fileName, $this->tmpDir . '_tmp.tmp');
                break; // exit from while
            }
            foreach ($exchanges as $exchange) {
                if (!$this->courseManager->getByExchangeAndDate($exchange, $date)) {
                    continue;
                }
                $typeOperation = $this->execute6($exchange, $date);
                switch ($typeOperation) {
                    case self::TYPE_OPERATION_BUY:
                        $balance = $this->accountManager->getBalanceByExchange($exchange);
                        if (Math::compare(0.001, $balance) >= 0) {
                            $сourse = $this->courseManager->getByExchangeAndDate($exchange, $date);
                            $this->buyInvest($сourse, self::SUM_INVESTMENT);
                            pr('buy - ' . $exchange->getName() . ' ' . $сourse->getDateFormatDMY());
                        }
                        break;
                    case self::TYPE_OPERATION_SELL:
                        $balance = $this->accountManager->getBalanceByExchange($exchange);
                        if (Math::compare($balance, 0.001) >= 0) {
                            $сourse = $this->courseManager->getByExchangeAndDate($exchange, $date);
                            $this->sellInvest($сourse, $balance);
                            pr('sell - ' . $exchange->getName() . ' ' . $сourse->getDateFormatDMY());
                        }
                        break;
                }
            }
            //===================================================================
            //===================================================================
            $date->add(new \DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }
        echo $date->format('d.m.Y');
        return $this->getResponse();
    }


    /**
     * покупаем при стабильно падающем тренде > 5 дней
     * и пересечении линии тренда
     * продаем при пересечении линии STOPLOSS
     * @param CourseCollection $courses
     */
    public function execute(Exchange $exchange, Date $date)
    {
        $dateStart = clone $date;
        $dateStart->sub(new \DateInterval('P6M'));
        $courses = $this->courseManager->fetchAllByExchangeAndPeriod($exchange, $dateStart, $date);
        $service = $this->serviceExp1;
        $service->init(new CourseCollection($courses));
        $lastCourse = $service->getLastCourse();
        if ($service->isDownTrend()) {
            $balance = $this->accountManager->getBalanceByExchange($lastCourse->getExchange());
            if ($service->countLastDownTrend() > 5 && Math::compare($balance, 0.001) <= 0 ) {
                if ($service->getLastCourseValue() > $service->getLastTrendValue()) {
                    $values = [];
                    $type = '-1';
                    $values7 = $service->getLast7ValuesChangeTrend();
                    if (TechnicalAnalysis::isDoubleBottom(array_slice($values7, 2))) {
                        $type = '7';
                        $values = array_slice($values7, 2, 7, true);
                    } elseif (TechnicalAnalysis::isTripleBottom($values7) || TechnicalAnalysis::isReverseHeadShoulders($values7) ) {
                        $type = '5';
                        $values = array_slice($values7, 0, 7, true);
                    }
                    if (count($values) && !$service->isCrossUpTrend($values, 0)) {
                        return self::TYPE_OPERATION_BUY;
//                        /** @var Investments $investment */
//                        $investment = $this->investManager->createEntity();
//                        $investment->setExchange($lastCourse->getExchange())
//                            ->setCourse($lastCourse->getValue())
//                            ->setDate($lastCourse->getDate())
//                            ->setSum(self::SUM_INVESTMENT)
//                            ->setAmount(self::SUM_INVESTMENT / $lastCourse->getValue());
//                        $this->investManager->buy($investment);
//                        pr('buy - ' . $lastCourse->getExchange()->getName() . ' ' . $type . ' = ' .$lastCourse->getDateFormatDMY());
                    }
                }
            }
        } else {
            $balance = $this->accountManager->getBalanceByExchange($lastCourse->getExchange());
            if ($balance > 0) {
                $stopLoss = $service->getLastTrendValue() * 0.97;
                if ($service->getLastCourseValue() < $stopLoss) {
                    return self::TYPE_OPERATION_SELL;
//                    /** @var Investments $investment */
//                    $investment = $this->investManager->createEntity();
//                    $investment->setExchange($lastCourse->getExchange())
//                        ->setCourse($lastCourse->getValue())
//                        ->setDate($lastCourse->getDate())
//                        ->setSum($balance * $lastCourse->getValue())
//                        ->setAmount($balance);
//                    $this->investManager->sell($investment);
//                    pr('sell - ' . $lastCourse->getExchange()->getName() . ' ' . $lastCourse->getDateFormatDMY());
                }
            }
        }
    }

    /**
     * покупка происходит при пересечении линии сопративления
     * продажа происходит при пересечении линии поддержки
     * @param CourseCollection $courses
     */
    public function execute2(Exchange $exchange, Date $date)
    {
        $dateStart = clone $date;
        $dateStart->sub(new \DateInterval('P6M'));
        $courses = $this->courseManager->fetchAllByExchangeAndPeriod($exchange, $dateStart, $date);
        $service = $this->serviceExp1;
        $service->init(new CourseCollection($courses));
        $lastCourse = $service->getLastCourse();
        $penultimateCourse = $service->getPenultimateCourse();
        $lastTrendValue = $service->getLastTrendValue();
        if ($lastCourse->getValue() > $lastTrendValue && $penultimateCourse->getValue() < $lastTrendValue) {
            return self::TYPE_OPERATION_BUY;
//            $investment = $this->investManager->createEntity();
//            $investment->setExchange($lastCourse->getExchange())
//                ->setCourse($lastCourse->getValue())
//                ->setDate($lastCourse->getDate())
//                ->setSum(self::SUM_INVESTMENT)
//                ->setAmount(self::SUM_INVESTMENT / $lastCourse->getValue());
//            $this->investManager->buy($investment);
//            pr('buy - ' . $lastCourse->getExchange()->getName() . ' ' . $lastCourse->getDateFormatDMY());
        } elseif ($penultimateCourse->getValue() > $lastTrendValue && $lastCourse->getValue() < $lastTrendValue) {
            return self::TYPE_OPERATION_SELL;
//            $balance = $this->accountManager->getBalanceByExchange($lastCourse->getExchange());
//            if ($balance > 0) {
//                /** @var Investments $investment */
//                $investment = $this->investManager->createEntity();
//                $investment->setExchange($lastCourse->getExchange())
//                    ->setCourse($lastCourse->getValue())
//                    ->setDate($lastCourse->getDate())
//                    ->setSum($balance * $lastCourse->getValue())
//                    ->setAmount($balance);
//                $this->investManager->sell($investment);
//                pr('sell - ' . $lastCourse->getExchange()->getName() . ' ' . $lastCourse->getDateFormatDMY());
//            }
        }
    }

    /**
     * This method work is wrong
     * @param Exchange $exchange
     * @param Date     $date
     *
     * @return string
     */
    public function execute3(Exchange $exchange, Date $date)
    {
        $flagBuy = false;
        $flagSell = false;
        $percents = [0.2, 0.6, 1, 1.35, 2];
        foreach ($percents as $percent) {
            // ищем фигуры разворота.
            // фигура W и M.
            $cacheCourses = $this->cacheCourseManager->fetch5ByExchangeAndPercent($exchange, $percent);
            if ($cacheCourses
                && $cacheCourses->countFirstData() >= self::STABLE_TREND
                && $cacheCourses->lastNullOperation()) {

                if ( $cacheCourses->firstIsUpTrend()
                    && TechnicalAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // покупаем
                    $flagBuy = true;
                }elseif ($cacheCourses->firstIsDownTrend()
                    && TechnicalAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // продаем
                    $flagSell = true;
                }
            }
            // =============================================================
            // фигура тройное дно, ReverseS&H, тройные вершины S&H
            $cacheCourses = $this->cacheCourseManager->fetch7ByExchangeAndPercent($exchange, $percent);
            if ($cacheCourses
                && $cacheCourses->countFirstData() >= self::STABLE_TREND
                && $cacheCourses->lastNullOperation() ) {

                if ($cacheCourses->firstIsUpTrend()) {
                    if (TechnicalAnalysis::isTripleBottom($cacheCourses->listLastValue(), $percent, $percent)
                        || TechnicalAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent)) {
                        // покупаем
                        $flagBuy = true;
                    }
                }elseif($cacheCourses->firstIsDownTrend()) {
                    if (TechnicalAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent)
                        || TechnicalAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                        // продаем
                        $flagSell = true;
                    }
                }
            }
        }

        if (!($flagSell && $flagBuy)) {
//            $сourse = $this->courseManager->getByExchangeAndDate($exchange, $date);
//            $balance = $this->accountManager->getBalanceByExchange($exchange);
            if ($flagBuy) {
                return self::TYPE_OPERATION_BUY;
//                $investment = $this->investManager->createEntity();
//                $investment->setExchange($exchange)
//                    ->setCourse($сourse->getValue())
//                    ->setDate($сourse->getDate())
//                    ->setSum(self::SUM_INVESTMENT)
//                    ->setAmount(self::SUM_INVESTMENT / $сourse->getValue());
//                $this->investManager->buy($investment);
//                pr('buy - ' . $exchange->getName() . ' ' . $сourse->getDateFormatDMY());
            } elseif ($flagSell) {
                return self::TYPE_OPERATION_SELL;
//                /** @var Investments $investment */
//                $investment = $this->investManager->createEntity();
//                $investment->setExchange($exchange)
//                    ->setCourse($сourse->getValue())
//                    ->setDate($сourse->getDate())
//                    ->setSum($balance * $сourse->getValue())
//                    ->setAmount($balance);
//                $this->investManager->sell($investment);
//                pr('sell - ' . $сourse->getExchange()->getName() . ' ' . $сourse->getDateFormatDMY());
            }
        } else {
            pr('$flagSell == $flagBuy '. $date->formatDMY());
        }
    }


    public function execute4(Exchange $exchange, Date $date)
    {
        $dateStart = clone $date;
        $dateStart->sub(new \DateInterval('P6M'));
        $courses = $this->courseManager->fetchAllByExchangeAndPeriod($exchange, $dateStart, $date);
        $service = $this->serviceExp1;
        $service->init(new CourseCollection($courses));

        $lastCourse = $service->getLastCourse();
        $balance = $this->accountManager->getBalanceByExchange($lastCourse->getExchange());
        if ($service->isUpTrend()) {
            if ($service->countLastUpTrend() >= 2 && Math::compare($balance, 0.001) <= 0 ) {
                $result = false;
                $values7 = $service->getLast7ValuesChangeTrend();
                if ($service->isDoubleBottom(array_slice($values7, 2))) {
                    $result = true;
                    file_put_contents('data/tmp/fugure', 'DB - '.$date->formatDMY()."\n", FILE_APPEND);
                } elseif ($service->isTripleBottom($values7)) {
                    $result = true;
                    file_put_contents('data/tmp/fugure', 'TB - '.$date->formatDMY()."\n", FILE_APPEND);
                } elseif ($service->isReverseHeadShoulders($values7)) {
                    $result = true;
                    file_put_contents('data/tmp/fugure', 'RHS - '.$date->formatDMY()."\n", FILE_APPEND);
                }
                if ($result) {
                    return self::TYPE_OPERATION_BUY;
                }
            }
        } else if ($service->isDownTrend()) {
            if (Math::compare(0.001, $balance) ) {
                $stopLoss = $service->getLastTrendValue() * 0.97;
                if ($service->getLastCourseValue() < $stopLoss) {
                    return self::TYPE_OPERATION_SELL;
                }
            }
        }
        return null;
    }

    /**
     * SUCCESS
     *
     * @param Exchange $exchange
     * @param Date     $date
     * @return null|string
     */
    public function execute5(Exchange $exchange, Date $date)
    {
        $dateStart = clone $date;
        $dateStart->sub(new \DateInterval('P6M'));
        $courses = $this->courseManager->fetchAllByExchangeAndPeriod($exchange, $dateStart, $date);
        $service = $this->serviceExp1;
        $service->init(new CourseCollection($courses));

        $lastCourse = $service->getLastCourse();
        $balance = $this->accountManager->getBalanceByExchange($lastCourse->getExchange());

        $result = false;
        $values7 = $service->getLast7ValuesChangeTrend();
        if ( Math::compare($balance, 0.001) <= 0 ) {
            if ($service->isDoubleBottom(array_slice($values7, 2))) {
                $result = true;
                file_put_contents('data/tmp/fugure', '+DB - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            } elseif ($service->isTripleBottom($values7)) {
                $result = true;
                file_put_contents('data/tmp/fugure', '+TB - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            } elseif ($service->isReverseHeadShoulders($values7)) {
                $result = true;
                file_put_contents('data/tmp/fugure', '+RHS - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            }
            if ($result) {
                return self::TYPE_OPERATION_BUY;
            }
        } else {
            $result = false;
            $values7 = $service->getLast7ValuesChangeTrend();
            if ($service->isTopBottom(array_slice($values7, 2))) {
                $result = true;
                file_put_contents('data/tmp/fugure', '-TB - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            } elseif ($service->isTripleTop($values7)) {
                $result = true;
                file_put_contents('data/tmp/fugure', '-TT - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            } elseif ($service->isHeadShoulders($values7)) {
                $result = true;
                file_put_contents('data/tmp/fugure', '-HS - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            }
            if ($result) {
                return self::TYPE_OPERATION_SELL;
            }
        }
        return null;
    }

    public function execute6(Exchange $exchange, Date $date)
    {
        $dateStart = clone $date;
        $dateStart->sub(new \DateInterval('P6M'));
        $courses = $this->courseManager->fetchAllByExchangeAndPeriod($exchange, $dateStart, $date);
        $service = $this->serviceExp1;
        $service->init(new CourseCollection($courses));

        $lastCourse = $service->getLastCourse();
        $balance = $this->accountManager->getBalanceByExchange($lastCourse->getExchange());
        if ( Math::compare($balance, 0.001) <= 0 ) {
            $values = [];
            $values7 = $service->getLast7ValuesChangeTrend();
            if ($service->isDoubleBottom(array_slice($values7, 2, 5))) {
                $values = array_slice($values7, 2, 5, true);
//                file_put_contents('data/tmp/fugure', '+DB - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            } elseif ($service->isTripleBottom($values7)) {
                $values = $values7;
//                file_put_contents('data/tmp/fugure', '+TB - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            } elseif ($service->isReverseHeadShoulders($values7)) {
                $values = $values7;
//                file_put_contents('data/tmp/fugure', '+RHS - '.$exchange->getName(). ' - '.$date->formatDMY()."\n", FILE_APPEND);
            }
            if (count($values) && $service->isValuesLowTrend(array_slice($values7, 1, -1, true))) {
                return self::TYPE_OPERATION_BUY;
            }
        } else {
            $stopLoss = $service->getLastTrendValue();
            if ($service->getLastCourseValue() < $stopLoss) {
                return self::TYPE_OPERATION_SELL;
            }
        }
        return null;
    }


    /**
     * @param Course $сourse
     * @param int    $sumInvest
     */
    private function buyInvest(Course $сourse, $sumInvest = self::SUM_INVESTMENT)
    {
        /** @var Investments $investment */
        $investment = $this->investManager->createEntity();
        $investment->setExchange($сourse->getExchange())
            ->setCourse($сourse->getValue())
            ->setDate($сourse->getDate())
            ->setSum($sumInvest)
            ->setAmount($sumInvest / $сourse->getValue());
        $this->investManager->buy($investment);
    }

    /**
     * @param Course $сourse
     * @param        $balance
     */
    private function sellInvest(Course $сourse, $balance)
    {
        /** @var Investments $investment */
        $investment = $this->investManager->createEntity();
        $investment->setExchange($сourse->getExchange())
            ->setCourse($сourse->getValue())
            ->setDate($сourse->getDate())
            ->setSum($balance * $сourse->getValue())
            ->setAmount($balance);
        $this->investManager->sell($investment);
    }

}
