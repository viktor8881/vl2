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

include(realpath(__DIR__.'/../Service/simple_html_dom.php'));

class StockUSAController extends AbstractActionController
{

    const URL_USA_STOCK = 'https://ru.tradingview.com/markets/stocks-usa/market-movers-large-cap/';

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
        $html = file_get_html(self::URL_USA_STOCK);
        if ($html) {
            foreach ($html->find('tr.tv-screener-table__result-row') as $tr) {
                $value = $tr->children(0);
                $secId = trim($value->find('a',0)->plaintext);
                $exchangeName = trim($value->find('.tv-screener__description', 0)->plaintext);

                $value2 = $tr->children(1);
                $rate = trim($value2->plaintext);

                if (!empty($secId) && !empty($exchangeName) && is_numeric($rate)) {
                    /** @var $exchange Exchange */
                    $exchange = $this->exchangeService->getByMoexSecid($secId);
                    $this->logger->info('start - '. $exchange->getId());
                    if ($exchange && $rate) {
                        if (!$exchange->getHide()) {
                            $tradeDate = new \DateTime();
                            $lastEntity = $this->moexService->lastByExchangeId($exchange->getId());

                            if (!$lastEntity || $lastEntity->getDate() != $tradeDate) {
                                $this->logger->info('preparate - '. $exchange->getId());
                                $entity = new Moex();
                                $entity->setExchange($exchange)
                                    ->setSecId($exchange->getMoexSecId())
                                    ->setRate($rate)
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
                    }
                    $this->logger->info('stop - '. $exchange->getId());
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
