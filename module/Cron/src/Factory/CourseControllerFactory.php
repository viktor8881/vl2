<?php
namespace Cron\Factory;


use Course\Service\CourseManager;
use Course\Service\CourseService;
use Cron\Controller\CourseController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CourseControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $tmpDir = $container->get('Config')['tmp_dir'];
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir);
        }

        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(CourseManager::class);
        $courseService = $container->get(CourseService::class);
        $controller = new CourseController($exchangeManager, $courseManager, $courseService, $tmpDir);
        return $controller;
    }

}