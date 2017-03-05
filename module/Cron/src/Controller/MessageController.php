<?php
namespace Cron\Controller;

use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Zend\Mvc\Controller\AbstractActionController;

class MessageController extends AbstractActionController
{
    /** @var TaskOvertimeAnalysisManager */
    private $taskOvertimeAnalysisManager;

    /** @var TaskPercentAnalysisManager */
    private $taskPercentAnalysisManager;
    
    /** @var FigureAnalysisManager */
    private $figureAnalysisManager;
    /** @var Mail */
    private $mail;

    public function __construct(TaskOvertimeAnalysisManager $taskOvertimeAnalysisManager,
                                TaskPercentAnalysisManager $taskPercentAnalysisManager,
                                FigureAnalysisManager $figureAnalysisManager,
                                Mail $mail) {
        $this->taskOvertimeAnalysisManager = $taskOvertimeAnalysisManager;
        $this->taskPercentAnalysisManager = $taskPercentAnalysisManager;
        $this->figureAnalysisManager = $figureAnalysisManager;
        $this->mail = $mail;
    }

    public function sendMessageAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime('16.02.2017');
        }

        $collOvertimeAnalysis = $this->taskOvertimeAnalysisManager->getCollectionByDate($dateNow);
        $collPercentAnalysis = $this->taskPercentAnalysisManager->getCollectionByDate($dateNow);
        $collFigureAnalysis = $this->figureAnalysisManager->getCollectionByDate($dateNow);

        $listExchange = array_merge($collOvertimeAnalysis->listExchange(), $collPercentAnalysis->listExchange(), $collFigureAnalysis->listExchange());

        if (count($listExchange)) {
            foreach ($listExchange as $exchange) {
                $this->mail->sendAnalysis($exchange,
                    $collOvertimeAnalysis->getByExchange($exchange),
                    $collPercentAnalysis->listByExchange($exchange),
                    $collFigureAnalysis->listByExchange($exchange));
            }
        }
        return $this->getResponse();
    }
}
