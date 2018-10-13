<?php

namespace Course\Service;


use Base\Service\Math;
use Course\Entity\Moex;
use Course\Entity\MoexCollection;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;

class MoexService
{

    const USD_SEC_ID    = 'USD000UTSTOM';
    const EUR_SEC_ID    = 'EUR_RUB__TOM';
    const GOLD_SEC_ID   = 'GLDRUB_TOM';
    const SILVER_SEC_ID = 'SLVRUB_TOM'; /** silver is not use yet  */

    const MOEX_URLS = [
        'https://iss.moex.com/iss/engines/currency/markets/selt/boards/CETS/securities.json?securities=USD000UTSTOM,EUR_RUB__TOM',
        'https://iss.moex.com/iss/engines/currency/markets/selt/boards/CETS/securities.json?securities=GLDRUB_TOM'
    ];

    private static $moexData = [];


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
     * @param Exchange $exchange
     * @return Moex[]
     */
    public function fetchAllByExchange(Exchange $exchange)
    {
        return $this->moexManager->fetchAllByExchangeId($exchange->getId());
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
     * @param int $exchangeId
     *
     * @return Moex|null
     */
    public function getNewEntityByExchangeId($exchangeId)
    {
        $lastMoex = $this->moexManager->lastByExchangeId($exchangeId);
        $moex = $this->receiveMoexData($exchangeId);
        if ($moex && $lastMoex && $moex->getValue() > 0 && Math::compareMoney($moex->getValue(), $lastMoex->getValue()) === 0) {
            return null;
        }
        return $moex;
    }


    /**
     * @return Moex[]
     */
    public function receiveMoexData($exchangeId = null)
    {
        if (!count(self::$moexData)) {
            $dateNow = new \DateTime();
            foreach (self::MOEX_URLS as $url) {
                $data = $this->getDataByMoexUrl($url);
                foreach ($data as $secId => $values) {
                    if ($values['value'] === null) {
                        continue;
                    }
                    $exchange = $this->exchangeManager->getByMoexSecid($secId);
                    $moexArray = [
                        'tradeDateTime' => $dateNow,
                        'secId' => $secId,
                        'rate' => $values['value'],
                        'exchange' => $exchange
                    ];
                    $moex = $this->moexManager->createEntity($moexArray);
                    self::$moexData[$exchange->getId()] = $moex;
                }
            }
        }

        if ($exchangeId) {
            return isset(self::$moexData[$exchangeId]) ? self::$moexData[$exchangeId] : null;
        }
        return self::$moexData;
    }

    /**
     * @param string $url
     * @return array
     */
    private function getDataByMoexUrl($url)
    {
        $data = [];
        $docs = file_get_contents($url);
        $dataDocs = json_decode($docs, true);
        if ($dataDocs) {
            foreach ($dataDocs['securities']['data'] as $value) {
                $data[$value[0]] = ['date' => $value[4], 'value' => 0];
            }
            foreach ($dataDocs['marketdata']['data'] as $value) {
                $data[$value[20]]['date'] .= ' ' . $value[34];
                $data[$value[20]]['value'] = $value[8];
            }
        }
        return $data;
    }

    /**
     * @deprecated
     * @param MoexCollection $collection
     */
    public function insertCollection(MoexCollection $collection)
    {
        /** @var $moex Moex */
        foreach ($collection->getIterator() as $moex) {
            $filename = $this->cacheDir . $moex->getExchangeId() . '.json';
            $content = substr(file_get_contents($filename), 0,-1) . ',[' . $moex->getTradeDateTimeForChart() . ', ' . (float)Math::round($moex->getRate(),2) .']]';
            file_put_contents($filename, $content);
            $this->moexManager->insert($moex);
        }
    }

    /**
     * @param Moex $moex
     * @return \Base\Entity\IEmpty
     */
    public function insert(Moex $moex)
    {
        return $this->moexManager->insert($moex);
    }



}