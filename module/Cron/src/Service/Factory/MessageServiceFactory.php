<?php
namespace Cron\Service\Factory;

use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\MovingAverage;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Base\Service\JpGraphService;
use Course\Service\CourseManager;
use Cron\Service\MessageService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MessageServiceFactory implements FactoryInterface
{


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskOvertimeAnalysisManager = $container->get(TaskOvertimeAnalysisManager::class);
        $taskPercentAnalysisManager = $container->get(TaskPercentAnalysisManager::class);
        $figureAnalysisManager = $container->get(FigureAnalysisManager::class);
        $movingAverage = $container->get(MovingAverage::class);
        $courseService = $container->get(CourseManager::class);
        $graphService = $container->get(JpGraphService::class);

        return new MessageService($taskOvertimeAnalysisManager, $taskPercentAnalysisManager, $figureAnalysisManager, $movingAverage, $courseService, $graphService);
    }

}