<?php
namespace Cron\Factory;

use Base\Service\MailService;
use Cron\Controller\MessageController;
use Cron\Controller\MoexMessageController;
use Cron\Service\MessageService;
use Cron\Service\MoexMessageService;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexMessageControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $messageService = $container->get(MoexMessageService::class);
        $exchangeManager = $container->get(ExchangeManager::class);
        $mailService = $container->get(MailService::class);

        return new MoexMessageController($messageService, $exchangeManager, $mailService);
    }

}