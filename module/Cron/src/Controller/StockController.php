<?php
namespace Cron\Controller;

use Analysis\Service\MoexAnalysisService;
use Course\Entity\Moex;
use Course\Service\MoexCacheCourseService;
use Course\Service\MoexService;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;

class StockController extends AbstractActionController
{

    const URL_STOCK = [
        'https://iss.moex.com/iss/history/engines/stock/markets/shares/boards/TQBR/securities.json',
        'https://iss.moex.com/iss/history/engines/stock/markets/shares/boards/TQBR/securities.json?start=100',
        'https://iss.moex.com/iss/history/engines/stock/markets/shares/boards/TQBR/securities.json?start=200',
        ];

    /** @var ExchangeManager */
    private $exchangeService;

    /** @var MoexService */
    private $moexService;

    /** @var MoexCacheCourseService */
    private $cacheCourseService;

    /** @var TaskPercentManager */
    private $taskPercentManager;

    /** @var TaskOvertimeManager */
    private $taskOvertimeManager;

    /** @var MoexAnalysisService */
    private $analysisService;

    /** @var Logger */
    private $logger;

    /**
     * StockController constructor.
     * @param ExchangeManager $exchangeService
     * @param MoexService $moexService
     */
    public function __construct(ExchangeManager $exchangeService,
                                MoexService $moexService,
                                MoexCacheCourseService $cacheCourseService,
                                TaskPercentManager $taskPercentManager,
                                TaskOvertimeManager $taskOvertimeManager,
                                MoexAnalysisService $analysisService,
                                Logger $logger)
    {
        $this->exchangeService = $exchangeService;
        $this->moexService = $moexService;
        $this->cacheCourseService = $cacheCourseService;

        $this->taskPercentManager = $taskPercentManager;
        $this->taskOvertimeManager = $taskOvertimeManager;
        $this->analysisService = $analysisService;

        $this->logger = $logger;
    }

    public function indexAction()
    {
        foreach (self::URL_STOCK as $url) {
            $dataRaw = file_get_contents($url);
            if ($dataRaw) {
                $data = json_decode($dataRaw, true);
                if ($data) {
                    foreach ($data['history']['data'] as $row) {
                        /** @var $exchange Exchange */
                        $exchange = $this->exchangeService->getByMoexSecid($row[3]);
                        if (!$exchange) {
                            $this->logger->debug(json_encode($row));
                            continue;
                        }
                        if ($row[9]) {
                            $this->logger->info('start - '. $exchange->getId());
                            if (!$exchange->getHide()) {
                                $tradeDate = new \DateTime($row[1]);
                                $lastEntity = $this->moexService->lastByExchangeId($exchange->getId());

                                if (!$lastEntity || $lastEntity->getDate() != $tradeDate) {
                                    $this->logger->info('preparate - '. $exchange->getId());
                                    $entity = new Moex();
                                    $entity->setExchange($exchange)
                                        ->setSecId($exchange->getMoexSecId())
                                        ->setRate($row[9])
                                        ->setTradeDateTime($tradeDate);
                                    $this->moexService->insert($entity);

                                    // second step
                                    $this->cacheCourseService->fillingCache($entity);

                                    // third step
                                    $this->analisis($exchange, $tradeDate);
                                }
                            } else {
                                $this->logger->info('skip because is hide on site - '. $exchange->getId());
                            }
                            $this->logger->info('stop - '. $exchange->getId());
                        }
                    }
                }
            }
        }
        return $this->getResponse();
    }


    /**
     * @param Exchange $exchange
     * @param \DateTime $dateNow
     */
    public function analisis(Exchange $exchange, \DateTime $dateNow)
    {
        /** @var TaskPercent $task */
        foreach ($this->taskPercentManager->fetchAll() as $task) {
            $this->analysisService->runPercentByTask($task, $dateNow, $exchange);
        }
        /** @var TaskPercent $task */
        foreach ($this->taskOvertimeManager->fetchAll() as $task) {
            $this->analysisService->runOvertimeByTask($task, $dateNow, $exchange);
        }

        foreach (MoexCacheCourseService::listPercent() as $percent) {
            $this->analysisService->technicalAnalysisByExchange($exchange, $dateNow, $percent);
        }
    }

}
