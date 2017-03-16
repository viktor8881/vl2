<?php
namespace Cron\Factory;


use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;


class MessageControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskOvertimeAnalysisManager = $container->get(TaskOvertimeAnalysisManager::class);
        $taskPercentAnalysisManager = $container->get(TaskPercentAnalysisManager::class);
        $figureAnalysisManager = $container->get(FigureAnalysisManager::class);

        $mail = $container->get(TaskOvertimeAnalysisManager::class);

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




        return new MessageController($taskOvertimeAnalysisManager, $taskPercentAnalysisManager, $figureAnalysisManager, $mail);
    }

}