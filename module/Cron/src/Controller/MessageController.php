<?php
namespace Cron\Controller;

use Analysis\Service\MovingAverage;
use Base\Service\MailService;
use Cron\Service\MessageService;
use Zend\Mvc\Controller\AbstractActionController;

class MessageController extends AbstractActionController
{

    /** @var MessageService */
    private $messageService;
    /** @var MovingAverage */
    private $movingAverage;
    /** @var MailService */
    private $mailService;


    /**
     * MessageController constructor.
     * @param MessageService $messageService
     * @param MovingAverage  $movingAverage
     * @param MailService    $mailService
     */
    public function __construct(MessageService $messageService, MovingAverage $movingAverage, MailService $mailService)
    {
        $this->messageService = $messageService;
        $this->movingAverage = $movingAverage;
        $this->mailService = $mailService;
    }


    public function sendMessageAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime();
        }

        $this->messageService->setDate($dateNow);
        $listExchange = $this->messageService->getListExchange();
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
