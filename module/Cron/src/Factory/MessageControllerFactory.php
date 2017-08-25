<?php
namespace Cron\Factory;

use Base\Service\MailService;
use Cron\Controller\MessageController;
use Cron\Service\MessageService;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MessageControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $messageService = $container->get(MessageService::class);
        $exchangeManager = $container->get(ExchangeManager::class);
        $mailService = $container->get(MailService::class);

        return new MessageController($messageService, $exchangeManager, $mailService);
    }

}