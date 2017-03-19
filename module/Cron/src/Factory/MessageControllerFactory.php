<?php
namespace Cron\Factory;


use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Base\Service\MailService;
use Cron\Controller\MessageController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MessageControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskOvertimeAnalysisManager = $container->get(
            TaskOvertimeAnalysisManager::class
        );
        $taskPercentAnalysisManager = $container->get(
            TaskPercentAnalysisManager::class
        );
        $figureAnalysisManager = $container->get(FigureAnalysisManager::class);

//        // =====================================================================
//        $mailOptions = $container->get('config')['mail'];
//        $transport = new SmtpTransport();
//        $options   = new SmtpOptions($mailOptions['smtpOptions']);
//        $transport->setOptions($options);
//
//        $message = new Message();
//        $message->addTo($mailOptions['addresses']['adminEmail']);
//        $message->addFrom($mailOptions['addresses']['siteEmail']);
//        $message->setSubject('Greetings and Salutations!');
//
//        $html = new MimePart("Sorry, <strong>I'm going</strong> to be late today!");
//        $html->type = "text/html";
//
//        $body = new MimeMessage();
//        $body->addPart($html);
//
//        $message->setBody($body);
//
//        $transport->send($message);
//        // =====================================================================
//        die('asdasd');

        $mail = $container->get(MailService::class);

        return new MessageController($taskOvertimeAnalysisManager, $taskPercentAnalysisManager, $figureAnalysisManager, $mail);
    }

}