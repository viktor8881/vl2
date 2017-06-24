<?php
namespace Cron\Controller;

use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\MovingAverage;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Base\Service\MailService;
use Zend\Mvc\Controller\AbstractActionController;

class MessageController extends AbstractActionController
{
    /** @var TaskOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;
    /** @var TaskPercentAnalysisManager */
    private $taskPercentAnalysisManager;
    /** @var FigureAnalysisManager */
    private $figureAnalysisManager;
    /** @var MovingAverage */
    private $movingAverage;
    /** @var MailService */
    private $mailService;


    public function __construct(TaskOvertimeAnalysisManager $taskOvertimeAnalysisManager,
                                TaskPercentAnalysisManager $taskPercentAnalysisManager,
                                FigureAnalysisManager $figureAnalysisManager,
                                MovingAverage $movingAverage,
                                MailService $mailService) {
        $this->taskOvertimeAnalysisManager = $taskOvertimeAnalysisManager;
        $this->taskPercentAnalysisManager = $taskPercentAnalysisManager;
        $this->figureAnalysisManager = $figureAnalysisManager;
        $this->movingAverage = $movingAverage;
        $this->mailService = $mailService;
    }


    public function sendMessageAction(\DateTime $dateNow = null)
    {
//        pr('sendMessageAction');
        if (is_null($dateNow)) {
            $dateNow = new \DateTime('18.04.2017');
        }

        $collOvertimeAnalysis = $this->taskOvertimeAnalysisManager->getCollectionByDate($dateNow);
        $collPercentAnalysis = $this->taskPercentAnalysisManager->getCollectionByDate($dateNow);
        $collFigureAnalysis = $this->figureAnalysisManager->getCollectionByDate($dateNow);

//        pr($collOvertimeAnalysis);
//        pr($collPercentAnalysis);
//        pr($collFigureAnalysis);

        $listExchange = array_merge($collOvertimeAnalysis->listExchange(), $collPercentAnalysis->listExchange(), $collFigureAnalysis->listExchange());
//        pr($listExchange);
        if (count($listExchange)) {
//            pr('$listExchange');
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
                    $collFigureAnalysis->listByExchange($exchange),
                    $this->movingAverage->getStatusCrossByExchangeAndDate($exchange, $dateNow)
                    );
            }
        }
        return $this->getResponse();
    }
}
