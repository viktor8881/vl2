<?php
namespace Task\Factory;

use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Controller\OvertimeController;
use Task\Service\TaskOvertimeManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class OvertimeControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskManager = $container->get(TaskOvertimeManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);
        $controller = new OvertimeController($taskManager, $exchangeManager);
        return $controller;
    }

}
