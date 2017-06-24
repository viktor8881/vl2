<?php
namespace Analysis\Service\Factory;

use Analysis\Service\MovingAverage;
use Course\Service\CourseManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MovingAverageFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $courseManager = $container->get(CourseManager::class);
        return new MovingAverage($courseManager);
    }

}