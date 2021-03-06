<?php
namespace Cron\Factory;


use Analysis\Service\AnalysisService;
use Cron\Controller\AnalysisController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\ServiceManager\Factory\FactoryInterface;


class AnalysisControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $analysisService = $container->get(AnalysisService::class);
        $taskPercentManager = $container->get(TaskPercentManager::class);
        $taskOvertimeManager = $container->get(TaskOvertimeManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);

        return new AnalysisController($exchangeManager, $taskPercentManager, $taskOvertimeManager, $analysisService);
    }

}