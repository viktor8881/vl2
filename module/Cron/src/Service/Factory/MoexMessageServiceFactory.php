<?php
namespace Cron\Service\Factory;

use Analysis\Service\MoexFigureAnalysisManager;
use Analysis\Service\MoexOvertimeAnalysisManager;
use Analysis\Service\MoexPercentAnalysisManager;
use Analysis\Service\MovingAverage;
use Base\Service\JpGraphService;
use Course\Service\MoexManager;
use Cron\Service\MoexMessageService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexMessageServiceFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskOvertimeAnalysisManager = $container->get(MoexOvertimeAnalysisManager::class);
        $taskPercentAnalysisManager = $container->get(MoexPercentAnalysisManager::class);
        $figureAnalysisManager = $container->get(MoexFigureAnalysisManager::class);
        $movingAverage = $container->get(MovingAverage::class);
        $courseService = $container->get(MoexManager::class);
        $graphService = $container->get(JpGraphService::class);

        return new MoexMessageService($taskOvertimeAnalysisManager, $taskPercentAnalysisManager, $figureAnalysisManager, $movingAverage, $courseService, $graphService);
    }

}