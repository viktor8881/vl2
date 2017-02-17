<?php
namespace Course\Service\Factory;

use Course\Service\CourseManager;
use Course\Service\CourseService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class CourseServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var CourseManager $courseManager */
        $courseManager = $container->get(CourseManager::class);
        $service = new CourseService($courseManager);
        return $service;
    }

}