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

        /** @var $moexCourse Moex */
        $list = [];
        foreach ($this->moexService->fetchAllByExchange($exchange) as $moexCourse) {
            $list[$moexCourse->getDateFormatDMY()] = $moexCourse->getValue();
        }

        $str ='';
        $countList = count($list);
        $i = 0;
        foreach ($list as $date => $value) {
            if (++$i === $countList) {
                $dateT = new \DateTime();
            } else {
                $dateT = new \DateTime($date);
                $dateT->setTime(23,59,59);
            }
            $str .= '[' . $dateT->format('U') . '000' . ', ' . $value . '],';
        }

        echo '[' . substr($str,0,-1) . ']';
        return $this->getResponse();


//        echo $this->moexService->dataChartByExchangeId($exchange->getId());
//        return $this->getResponse();
    }
}
