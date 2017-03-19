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
use Zend\View\Model\ViewModel;

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


    public function tmpAction()
    {
        $message = new Message();
        $message->addTo('matthew@example.org');
        $message->addFrom('ralph@example.org');
        $message->setSubject('Greetings and Salutations!');
        $message->setBody("Sorry, I'm going to be late today!");

        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options   = new SmtpOptions([
            'name'              => 'localhost.localdomain',
            'host'              => '127.0.0.1',
            'connection_class'  => 'login',
            'connection_config' => [
                'username' => 'user',
                'password' => 'pass',
            ],
        ]);
        $transport->setOptions($options);
        $transport->send($message);
        return $this->getResponse();
    }


    public function sendMessageAction(\DateTime $dateNow = null)
    {
        if (is_null($dateNow)) {
            $dateNow = new \DateTime('18.02.2017');
        }

        $collOvertimeAnalysis = $this->taskOvertimeAnalysisManager->getCollectionByDate($dateNow);
        $collPercentAnalysis = $this->taskPercentAnalysisManager->getCollectionByDate($dateNow);
        $collFigureAnalysis = $this->figureAnalysisManager->getCollectionByDate($dateNow);

        $listExchange = array_merge($collOvertimeAnalysis->listExchange(), $collPercentAnalysis->listExchange(), $collFigureAnalysis->listExchange());

        if (count($listExchange)) {
            foreach ($listExchange as $exchange) {
                $this->mailService->sendAnalysis($exchange,
                    $collOvertimeAnalysis->getByExchange($exchange),
                    $collPercentAnalysis->listByExchange($exchange),
                    $collFigureAnalysis->listByExchange($exchange));
            }
        }
        return $this->getResponse();
    }
}
