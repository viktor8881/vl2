<?php
namespace Cron\Factory;

use Course\Service\MoexCacheCourseManager;
use Course\Service\MoexCacheCourseService;
use Course\Service\MoexManager;
use Course\Service\MoexService;
use Cron\Controller\MoexCacheCourseController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MoexCacheCourseControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(MoexManager::class);
        $courseService = $container->get(MoexService::class);
        $cacheCourseManager = $container->get(MoexCacheCourseManager::class);
        $cacheCourseService = $container->get(MoexCacheCourseService::class);
        return new MoexCacheCourseController($exchangeManager,
            $courseManager, $courseService,
            $cacheCourseManager, $cacheCourseService);
    }

}