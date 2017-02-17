<?php
namespace Cron\Factory;

use Exchange\Service\ExchangeManager;
use Course\Service\CourseManager;
use Course\Service\CourseService;
use Course\Service\CacheCourseManager;
use Course\Service\CacheCourseService;
use Cron\Controller\CacheCourseController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheCourseControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(CourseManager::class);
        $courseService = $container->get(CourseService::class);
        $cacheCourseManager = $container->get(CacheCourseManager::class);
        $cacheCourseService = $container->get(CacheCourseService::class);
        
        return new CacheCourseController($exchangeManager,
            $courseManager, $courseService,
            $cacheCourseManager, $cacheCourseService);
    }

}