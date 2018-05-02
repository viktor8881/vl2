<?php
namespace Cron\Controller;

use Analysis\Service\MoexAnalysisService;
use Course\Service\MoexCacheCourseService;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Task\Entity\TaskPercent;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\Mvc\Controller\AbstractActionController;

class MoexAnalysisController extends AbstractActionController
{

    const COUNT_RUN_AT_TIME = 30;


    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var TaskPercentManager */
    private $taskPercentManager;

    /** @var TaskOvertimeManager */
    private $taskOvertimeManager;

    /** @var MoexAnalysisService */
    private $analysisService;

    public function __construct(ExchangeManager $exchangeManager,
        TaskPercentManager $taskPercentManager,
        TaskOvertimeManager $taskOvertimeManager,
        MoexAnalysisService $analysisService
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
    public function indexAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime();
        }

        /** @var TaskPercent $task */
        foreach ($this->taskPercentManager->fetchAll() as $task) {
            $this->analysisService->analysisByTask($task, $dateNow);
        }

        /** @var Exchange $exchange */
        foreach ($this->exchangeManager->fetchAllMoex() as $exchange) {
            foreach (MoexCacheCourseService::listPercent() as $percent) {
                $this->analysisService->technicalAnalysisByExchange($exchange, $dateNow, $percent);
            }
        }
        return $this->getResponse();
    }
}
