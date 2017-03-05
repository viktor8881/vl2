<?php
namespace Task\Factory;

use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Controller\PercentController;
use Task\Service\TaskPercentManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class PercentControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskManager = $container->get(TaskPercentManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);
        $controller = new PercentController($taskManager, $exchangeManager);
        return $controller;
    }

}
