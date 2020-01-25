<?php
namespace Cron\Controller;

use Base\Service\MailService;
use Cron\Service\MoexMessageService;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;

class MoexMessageController extends AbstractActionController
{

    /** @var MoexMessageService */
    private $messageService;
    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var MailService */
    private $mailService;


    /**
     * MessageController constructor.
     * @param MoexMessageService $messageService
     * @param ExchangeManager  $movingAverage
     * @param MailService    $mailService
     */
    public function __construct(MoexMessageService $messageService, ExchangeManager $exchangeManager, MailService $mailService)
    {
        $this->messageService = $messageService;
        $this->exchangeManager = $exchangeManager;
        $this->mailService = $mailService;
    }


    public function indexAction()
    {
        $exchangeId = $this->params('exchangeId');
        $exchange = $this->exchangeManager->get($exchangeId);
        $this->messageService->setExchange($exchange);
        $this->mailService->sendAnalysis($this->messageService);
        $this->getResponse()->setStatusCode(200);
        return $this->getResponse();
    }


    public function favoriteAction()
    {
        $listExchange = $this->exchangeManager->fetchAllFavorite($this->params()->fromQuery('page', 1));
        if (count($listExchange)) {
            $this->mailService->sendAnalysisByExchanges($this->messageService, $listExchange);
        }
        return $this->getResponse();
    }

}
