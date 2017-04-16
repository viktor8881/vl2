<?php
namespace Cron\Controller;

use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Base\Service\MailService;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mvc\Controller\AbstractActionController;

class MessageController extends AbstractActionController
{
    /** @var TaskOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;
    /** @var TaskPercentAnalysisManager */
    private $taskPercentAnalysisManager;
    /** @var FigureAnalysisManager */
    private $figureAnalysisManager;
    /** @var MailService */
    private $mailService;


    public function __construct(TaskOvertimeAnalysisManager $taskOvertimeAnalysisManager,
                                TaskPercentAnalysisManager $taskPercentAnalysisManager,
                                FigureAnalysisManager $figureAnalysisManager,
                                MailService $mailService) {
        $this->taskOvertimeAnalysisManager = $taskOvertimeAnalysisManager;
        $this->taskPercentAnalysisManager = $taskPercentAnalysisManager;
        $this->figureAnalysisManager = $figureAnalysisManager;
        $this->mailService = $mailService;
    }


    public function sendMessageAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime();
        }

        $collOvertimeAnalysis = $this->taskOvertimeAnalysisManager->getCollectionByDate($dateNow);
        $collPercentAnalysis = $this->taskPercentAnalysisManager->getCollectionByDate($dateNow);
        $collFigureAnalysis = $this->figureAnalysisManager->getCollectionByDate($dateNow);

        $listExchange = array_merge($collOvertimeAnalysis->listExchange(), $collPercentAnalysis->listExchange(), $collFigureAnalysis->listExchange());
        if (count($listExchange)) {
            $listSended = [];
            foreach ($listExchange as $exchange) {
                if (in_array($exchange->getId(), $listSended)) {
                    continue;
                }
                $listSended[] = $exchange->getId();
                $this->mailService->sendAnalysis(
                    $dateNow,
                    $exchange,
                    $collOvertimeAnalysis->getByExchange($exchange),
                    $collPercentAnalysis->listByExchange($exchange),
                    $collFigureAnalysis->listByExchange($exchange));
            }
        }
        return $this->getResponse();
    }
}
