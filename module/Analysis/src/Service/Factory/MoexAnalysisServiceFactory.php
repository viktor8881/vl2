<?php
namespace Analysis\Service\Factory;

use Analysis\Service\MoexAnalysisService;
use Analysis\Service\MoexFigureAnalysisManager;
use Analysis\Service\MoexOvertimeAnalysisManager;
use Analysis\Service\MoexPercentAnalysisManager;
use Course\Service\MoexCacheCourseManager;
use Course\Service\MoexManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MoexAnalysisServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        $courseManager = $container->get(MoexManager::class);
        $figureAnalysisManager = $container->get(MoexFigureAnalysisManager::class);
        $taskPercentAnalysisManager = $container->get(MoexPercentAnalysisManager::class);
        $taskOvertimeAnalysisManager = $container->get(MoexOvertimeAnalysisManager::class);
        $cacheCourseManager = $container->get(MoexCacheCourseManager::class);

        return new MoexAnalysisService(
            $courseManager, $figureAnalysisManager, $taskPercentAnalysisManager,
            $taskOvertimeAnalysisManager, $cacheCourseManager
        );
    }

}