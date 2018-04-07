<?php

namespace Course\Service;


use Base\Service\Math;
use Course\Entity\Moex;
use Course\Entity\MoexCollection;
use Exchange\Service\ExchangeManager;

class MoexService
{

    const USD_SEC_ID = 'USD/RUB';
    const EUR_SEC_ID = 'EUR/RUB';
    const LIST_SEC_ID = [self::USD_SEC_ID, self::EUR_SEC_ID];

    const URL_CURRENCY_COURSES = 'http://iss.moex.com/iss/statistics/engines/futures/markets/indicativerates/securities.json';

    /** @var string */
    private $cacheDir;

    /** @var MoexManager */
    private $moexManager;

    /** @var ExchangeManager */
    private $exchangeManager;

    /**
     * MoexService constructor.
     * @param MoexManager $moexManager
     * @param ExchangeManager $exchangeManager
     */
    public function __construct(MoexManager $moexManager, ExchangeManager $exchangeManager, $cacheDir)
    {
        $this->moexManager = $moexManager;
        $this->exchangeManager = $exchangeManager;
        $this->cacheDir = $cacheDir . 'exchange';
    }

    /**
     * @param int $exchangeId
     * @return array
     */
    public function dataChartByExchangeId($exchangeId)
    {
        $filename = $this->cacheDir .  $exchangeId . '.json';
        if (file_exists($filename) && is_readable($filename)) {
            return file_get_contents($filename);
        }
        return '';
    }

    /**
     * @return MoexCollection
     */
    public function receiveLast()
    {
        $repository = new MoexCollection();
        $xmlstr = json_decode(file_get_contents(self::URL_CURRENCY_COURSES));
        if ($xmlstr) {
            foreach ($xmlstr->securities->data as $data) {
                if (!in_array($data[2], self::LIST_SEC_ID)) {
                    continue;
                }
                $moexArray = [
                    'tradeDateTime' => new \DateTime($data[0]. ' ' . $data[1]),
                    'secid' => $data[2],
                    'rate' => $data[3],
                    'exchange' => $this->exchangeManager->getByMoexSecid($data[2])
                ];
                $moex = $this->moexManager->createEntity($moexArray);
                $repository->append($moex);
            }
        }
        return $repository;
    }


    /**
     * @param \DateTime $dateTime
     * @return Moex
     */
    public function hasByDate(\DateTime $dateTime)
    {
        return $this->moexManager->hasByDate($dateTime);
    }

    /**
     * @param MoexCollection $collection
     */
    public function insertRepository(MoexCollection $collection)
    {
        /** @var $moex Moex */
        foreach ($collection->getIterator() as $moex) {
            $filename = $this->cacheDir . $moex->getExchangeId() . '.json';
            $content = substr(file_get_contents($filename), 0,-1) . ',[' . $moex->getTradeDateTimeForChart() . ', ' . (float)Math::round($moex->getRate(),2) .']]';
            file_put_contents($filename, $content);
            $this->moexManager->insert($moex);
        }
    }





}