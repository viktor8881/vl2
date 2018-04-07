<?php

namespace Course\Controller;

use Course\Entity\Moex;
use Course\Service\MoexService;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class MoexController extends AbstractActionController
{

    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var MoexService */
    private $moexService;


    public function __construct(MoexService $moexService, ExchangeManager $exchangeManager) {
        $this->moexService = $moexService;
        $this->exchangeManager = $exchangeManager;
    }

    /**
     * @return JsonModel
     * @throws \Exception
     */
    public function indexAction()
    {
        /** @var $exchange Exchange */
        $exchange = $this->exchangeManager->get($this->params()->fromRoute('id', -1));
        if (!$exchange) {
            throw new \Exception('Not found exchange.');
        }

        echo $this->moexService->dataChartByExchangeId($exchange->getId());
        return $this->getResponse();
    }
}
