<?php

namespace Base\Mail;

use Exchange\Entity\Exchange;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\View\Model\ViewModel;

class Mail
{

    /** @var Message */
    private $message;
    /** @var SmtpTransport */
    private $transport;

    public function __construct(Message $message, SmtpTransport $transport)
    {
        $this->message = $message;
        $this->transport = $transport;
    }

    public function sendAnalysis(Exchange $exchange,
        TaskOvertimeAnalysis $taskOvertimeAnalysis, array $taskPercentAnalyzes,
        array $taskFigureAnalyzes
    ) {
        $this->message->setSubject($exchange->getName());
        $view = new ViewModel(
            ['exchange'             => $exchange,
             'taskOvertimeAnalysis' => $taskOvertimeAnalysis,
             'taskPercentAnalyzes'  => $taskPercentAnalyzes,
             'taskFigureAnalyzes'   => $taskFigureAnalyzes]
        );
        $view->setTemplate('mail-analysis');

        $this->message->setBody();
        $this->transport->send($this->message);
    }

}