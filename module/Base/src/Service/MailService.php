<?php

namespace Base\Service;

use Analysis\Entity\TaskOvertimeAnalysis;
use Exchange\Entity\Exchange;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;


class MailService
{

    /** @var Message */
    private $message;
    /** @var SmtpTransport */
    private $transport;
    /** @var PhpRenderer */
    private $renderer;

    /**
     * MailService constructor.
     *
     * @param Message       $message
     * @param SmtpTransport $transport
     */
    public function __construct(Message $message, SmtpTransport $transport, PhpRenderer $renderer)
    {
        $this->message = $message;
        $this->transport = $transport;
        $this->renderer = $renderer;
    }

    /**
     * @param Exchange             $exchange
     * @param TaskOvertimeAnalysis $taskOvertimeAnalysis
     * @param TaskPercentAnalyzes[] $taskPercentAnalyzes
     * @param TaskFigureAnalyzes[]  $taskFigureAnalyzes
     */
    public function sendAnalysis(Exchange $exchange, TaskOvertimeAnalysis $taskOvertimeAnalysis, array $taskPercentAnalyzes, array $taskFigureAnalyzes)
    {




        // =====================================================================
        $this->message->setSubject($exchange->getName());

//        $renderer = new PhpRenderer();
//        $resolver   = new TemplateMapResolver();
//        $resolver->setMap(array('mailTemplate' => __DIR__ . '/../../view/mail/analysis.phtml'));
//        $renderer->setResolver($resolver);

        $viewModel = new ViewModel(
            ['exchange'             => $exchange,
             'taskOvertimeAnalysis' => $taskOvertimeAnalysis,
             'taskPercentAnalyzes'  => $taskPercentAnalyzes,
             'taskFigureAnalyzes'   => $taskFigureAnalyzes]
        );
        $viewModel->setTemplate('mail/analysis.phtml');
        $html = $this->renderer->render($viewModel);
        pr($html); exit;

//        $html = new MimePart("Sorry, <strong>I'm going</strong> to be late today!");
        $html = new MimePart($html);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($html);

        $this->message->setBody($body);
        $this->transport->send($this->message);
    }

}