<?php

namespace Base\Service;

use Cron\Service\MessageInterface;
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
     * @param MessageInterface $messageService
     */
    public function sendAnalysis(MessageInterface $messageService)
    {
        $html = $this->getHtmlBodyEmail($messageService);

        $htmlMimePart = new MimePart($html);
        $htmlMimePart->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($htmlMimePart);
        $this->message->setSubject($messageService->getSubject() . ' - dev');
        $this->message->setBody($body);
        $this->transport->send($this->message);
    }

    /**
     * @param MessageInterface $messageService
     * @param array $exchanges
     */
    public function sendAnalysisByExchanges(MessageInterface $messageService, array $exchanges)
    {
        $htmlBody = '';

        foreach ($exchanges as $exchange) {
            $messageService->setExchange($exchange);
            $htmlBody .= $this->getHtmlBodyEmail($messageService);
        }

        $this->message->setSubject('Summary - dev');
        $htmlMimePart = new MimePart($htmlBody);
        $htmlMimePart->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($htmlMimePart);
        $this->message->setBody($body);
        $this->transport->send($this->message);
    }

    /**
     * @param MessageInterface $messageService
     * @return string
     */
    private function getHtmlBodyEmail(MessageInterface $messageService)
    {
        $viewModel = new ViewModel(
            [   'date'                 => $messageService->getDate(),
                'exchange'             => $messageService->getExchange(),
                'taskOvertimeAnalysis' => $messageService->getAnalyzesOvertimeTask(),
                'taskPercentAnalyzes'  => $messageService->getAnalyzesPercentTask(),
                'taskFigureAnalyzes'   => $messageService->getListAnalyzesFigureTask(),
                'statusCrossAvg'       => $messageService->getStatusCross(),
                'srcGraph'             => $messageService->getSrcGraph()
            ]
        );
        $viewModel->setTemplate('mail/analysis.phtml');
        return $this->renderer->render($viewModel);
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