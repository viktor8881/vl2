<?php
namespace Analysis\Service\Factory;

use Analysis\Service\AnalysisService;
use Analysis\Service\FigureAnalysisManager;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Analysis\Service\TaskPercentAnalysisManager;
use Course\Service\CacheCourseManager;
use Course\Service\CourseManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AnalysisServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        $courseManager = $container->get(CourseManager::class);
        $figureAnalysisManager = $container->get(FigureAnalysisManager::class);
        $taskPercentAnalysisManager = $container->get(TaskPercentAnalysisManager::class);
        $taskOvertimeAnalysisManager = $container->get(TaskOvertimeAnalysisManager::class);
        $cacheCourseManager = $container->get(CacheCourseManager::class);

        return new AnalysisService(
            $courseManager, $figureAnalysisManager, $taskPercentAnalysisManager,
            $taskOvertimeAnalysisManager, $cacheCourseManager
        );
    }

}