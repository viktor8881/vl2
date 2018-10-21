<?php
namespace Cron\Controller;

use Course\Entity\Moex;
use Course\Service\MoexService;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
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

    /**
     * StockController constructor.
     * @param ExchangeManager $exchangeService
     * @param MoexService $moexService
     */
    public function __construct(ExchangeManager $exchangeService, MoexService $moexService)
    {
        $this->exchangeService = $exchangeService;
        $this->moexService = $moexService;
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
                        if ($exchange && $row[9]) {
                            $tradeDate = new \DateTime($row[1]);
                            $lastEntity = $this->moexService->lastByExchangeId($exchange->getId());
                            
                            if (!$lastEntity || $lastEntity->getDate() != $tradeDate) {
                                $entity = new Moex();
                                $entity->setExchange($exchange)
                                    ->setSecId($exchange->getMoexSecId())
                                    ->setRate($row[9])
                                    ->setTradeDateTime($tradeDate);
                                $this->moexService->insert($entity);
                            }

                        }
                    }
                }
            }
        }
        return $this->getResponse();
    }
}
