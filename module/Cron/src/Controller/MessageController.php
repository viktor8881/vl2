<?php
namespace Cron\Controller;

use Base\Service\MailService;
use Cron\Service\MessageService;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;

class MessageController extends AbstractActionController
{

    /** @var MessageService */
    private $messageService;
    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var MailService */
    private $mailService;


    /**
     * MessageController constructor.
     * @param MessageService $messageService
     * @param ExchangeManager  $movingAverage
     * @param MailService    $mailService
     */
    public function __construct(MessageService $messageService, ExchangeManager $exchangeManager, MailService $mailService)
    {
        $this->messageService = $messageService;
        $this->exchangeManager = $exchangeManager;
        $this->mailService = $mailService;
    }


    public function sendMessageAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime();
        }

        $this->messageService->setDate($dateNow);
        $listExchange = $this->exchangeManager->fetchAll();
        if (count($listExchange)) {
            $listSended = [];
            foreach ($listExchange as $exchange) {
                if (in_array($exchange->getId(), $listSended)) {
                    continue;
                }
                $listSended[] = $exchange->getId();
                $this->mailService->sendAnalysis($exchange, $this->messageService);
            }
        }
        return $this->getResponse();
    }
}
