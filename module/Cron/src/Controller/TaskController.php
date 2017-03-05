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

class TaskController extends AbstractActionController
{

    const COUNT_RUN_AT_TIME = 100;


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

    /**
     * @param \DateTime|null $dateNow
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function taskAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime('16.02.2017');
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
