<?php
namespace Cron\Factory;


use Analysis\Service\MoexAnalysisService;
use Cron\Controller\MoexAnalysisController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexAnalysisControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $analysisService = $container->get(MoexAnalysisService::class);
        $taskPercentManager = $container->get(TaskPercentManager::class);
        $taskOvertimeManager = $container->get(TaskOvertimeManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);

        return new MoexAnalysisController($exchangeManager, $taskPercentManager, $taskOvertimeManager, $analysisService);
    }

}