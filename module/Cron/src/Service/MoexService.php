<?php

namespace Cron\Service;


use Cron\Entity\Moex;
use Cron\Entity\MoexCollection;
use Exchange\Service\ExchangeManager;

class MoexService
{

    const USD_SEC_ID = 'USD/RUB';
    const EUR_SEC_ID = 'EUR/RUB';
    const LIST_SEC_ID = [self::USD_SEC_ID, self::EUR_SEC_ID];

    const URL_CURRENCY_COURSES = 'http://iss.moex.com/iss/statistics/engines/futures/markets/indicativerates/securities.json';

    /** @var MoexManager */
    private $moexManager;

    /** @var ExchangeManager */
    private $exchangeManager;

    /**
     * MoexService constructor.
     * @param MoexManager $moexManager
     * @param ExchangeManager $exchangeManager
     */
    public function __construct(MoexManager $moexManager, ExchangeManager $exchangeManager)
    {
        $this->moexManager = $moexManager;
        $this->exchangeManager = $exchangeManager;
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
     * @param MoexCollection $repository
     */
    public function insertRepository(MoexCollection $repository)
    {
        foreach ($repository->getIterator() as $moex) {
            $this->moexManager->insert($moex);
        }
    }





}