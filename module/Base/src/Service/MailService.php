<?php

namespace Base\Service;

use Cron\Service\MessageService;
use Exchange\Entity\Exchange;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;


class MailService
{

    /** @var Message */
    private $message;
    /** @var TransportInterface */
    private $transport;
    /** @var PhpRenderer */
    private $renderer;

    /**
     * MailService constructor.
     *
     * @param Message       $message
     * @param TransportInterface $transport
     */
    public function __construct(Message $message, TransportInterface $transport, PhpRenderer $renderer)
    {
        $this->message = $message;
        $this->transport = $transport;
        $this->renderer = $renderer;
    }


    /**
     * @param Exchange       $exchange
     * @param MessageService $messageService
     */
    public function sendAnalysis(Exchange $exchange, MessageService $messageService)
    {
        $this->message->setSubject($exchange->getName() . ' - dev');

        $viewModel = new ViewModel(
            ['date'                 => $messageService->getDate(),
             'exchange'             => $exchange,
             'taskOvertimeAnalysis' => $messageService->getAnalyzesOvertimeTaskByExchange($exchange),
             'taskPercentAnalyzes'  => $messageService->getAnalyzesPercentTaskByExchange($exchange),
             'taskFigureAnalyzes'   => $messageService->getListAnalyzesFigureTaskByExchange($exchange),
             'statusCrossAvg'       => $messageService->getStatusCrossByExchange($exchange),
             'srcGraph'             => $messageService->getSrcGraph($exchange)
            ]
        );
        $viewModel->setTemplate('mail/analysis.phtml');

        $html = $this->renderer->render($viewModel);
        $htmlMimePart = new MimePart($html);
        $htmlMimePart->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($htmlMimePart);
        $this->message->setBody($body);
        $this->transport->send($this->message);
    }


    public function testImg($srcImg)
    {
        $this->message->setSubject('TEST GRAPH - dev');
        $viewModel = new ViewModel(['srcImg' => $srcImg]);
        $viewModel->setTemplate('mail/test.phtml');

        $html = $this->renderer->render($viewModel);
        $htmlMimePart = new MimePart($html);
        $htmlMimePart->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($htmlMimePart);
        $this->message->setBody($body);
        $this->transport->send($this->message);
    }

}