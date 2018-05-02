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


    public function sendMessageAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime('22.04.2018');
        }

        $this->messageService->setDate($dateNow);
        $listExchange = $this->exchangeManager->fetchAllMoex();
        if (count($listExchange)) {
            foreach ($listExchange as $exchange) {
                $this->messageService->setExchange($exchange);
                $this->mailService->sendAnalysis($this->messageService);
            }
        }
        return $this->getResponse();
    }
}
