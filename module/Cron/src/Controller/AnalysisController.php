<?php
namespace Cron\Controller;

use Analysis\Service\AnalysisService;
use Course\Service\CacheCourseService;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Task\Entity\TaskOvertime;
use Task\Entity\TaskPercent;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\Mvc\Controller\AbstractActionController;
use Base\Service\Date;

class AnalysisController extends AbstractActionController
{

    const COUNT_RUN_AT_TIME = 30;


    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var TaskPercentManager */
    private $taskPercentManager;

    /** @var TaskOvertimeManager */
    private $taskOvertimeManager;

    /** @var AnalysisService */
    private $analysisService;

    public function __construct(ExchangeManager $exchangeManager,
        TaskPercentManager $taskPercentManager,
        TaskOvertimeManager $taskOvertimeManager,
        AnalysisService $analysisService
    ) {
        $this->exchangeManager = $exchangeManager;
        $this->taskPercentManager = $taskPercentManager;
        $this->taskOvertimeManager = $taskOvertimeManager;
        $this->analysisService = $analysisService;
    }

    public function tmpAction()
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
                echo 'final';
                exit;
            }
            $this->indexAction(clone $date);
            $date->add(new \DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }
        echo 'ok';
        return $this->getResponse();
    }

    /**
     * @param \DateTime|null $dateNow
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function indexAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime();
        }

        /** @var TaskPercent $taskPercent */
        foreach ($this->taskPercentManager->fetchAll() as $taskPercent) {
            $this->analysisService->runPercentByTask($taskPercent, $dateNow);
        }

        /** @var TaskOvertime $taskOvertime */
        foreach ($this->taskOvertimeManager->fetchAll() as $taskOvertime) {
            $this->analysisService->runOvertimeByTask($taskOvertime, $dateNow);
        }

        /** @var Exchange $exchange */
        foreach ($this->exchangeManager->fetchAllMetal() as $exchange) {
            foreach (CacheCourseService::listPercent() as $percent) {
                $this->analysisService->technicalAnalysisByExchange($exchange, $dateNow, $percent);
            }
        }
        return $this->getResponse();
    }
}
