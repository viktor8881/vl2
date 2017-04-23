<?php

namespace Base\Service;

use Analysis\Entity\TaskOvertimeAnalysis;
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
     * @param \DateTime                 $date
     * @param Exchange                  $exchange
     * @param TaskOvertimeAnalysis|null $taskOvertimeAnalysis
     * @param array                     $taskPercentAnalyzes
     * @param array                     $taskFigureAnalyzes
     */
    public function sendAnalysis(\DateTime $date, Exchange $exchange, TaskOvertimeAnalysis $taskOvertimeAnalysis = null, $taskPercentAnalyzes = [], $taskFigureAnalyzes = [])
    {
        $this->message->setSubject($exchange->getName() . ' - dev');
        $viewModel = new ViewModel(
            ['date'                 => $date,
             'exchange'             => $exchange,
             'taskOvertimeAnalysis' => $taskOvertimeAnalysis,
             'taskPercentAnalyzes'  => $taskPercentAnalyzes,
             'taskFigureAnalyzes'   => $taskFigureAnalyzes]
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

}