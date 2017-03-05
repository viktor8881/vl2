<?php
namespace Cron\Factory;


use Analysis\Service\AnalysisService;
use Cron\Controller\TaskController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\ServiceManager\Factory\FactoryInterface;


class TaskControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $analysisService = $container->get(AnalysisService::class);
        $taskPercentManager = $container->get(TaskPercentManager::class);
        $taskOvertimeManager = $container->get(TaskOvertimeManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);

        return new TaskController($exchangeManager, $taskPercentManager, $taskOvertimeManager, $analysisService);
    }

}