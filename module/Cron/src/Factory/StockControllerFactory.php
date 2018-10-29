<?php
namespace Cron\Factory;

use Analysis\Service\MoexAnalysisService;
use Course\Service\MoexCacheCourseService;
use Course\Service\MoexService;
use Cron\Controller\StockController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;


class StockControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $moexService = $container->get(MoexService::class);

        $cacheCourseService = $container->get(MoexCacheCourseService::class);

        $taskPercentManager = $container->get(TaskPercentManager::class);
        $taskOvertimeManager = $container->get(TaskOvertimeManager::class);
        $analysisService = $container->get(MoexAnalysisService::class);

        $config = $container->get('config');
        $logger = new Logger($config['logger-stock']);

        return new StockController($exchangeManager,
            $moexService,
            $cacheCourseService,
            $taskPercentManager,
            $taskOvertimeManager,
            $analysisService,
            $logger);
    }

}