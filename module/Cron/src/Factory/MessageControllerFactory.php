<?php
namespace Cron\Factory;


use Analysis\Service\MovingAverage;
use Base\Service\MailService;
use Cron\Controller\MessageController;
use Cron\Service\MessageService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MessageControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $messageService = $container->get(MessageService::class);
        $movingAverage = $container->get(MovingAverage::class);
        $mailService = $container->get(MailService::class);

        return new MessageController($messageService, $movingAverage, $mailService);
    }

}