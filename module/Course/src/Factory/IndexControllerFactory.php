<?php
namespace Course\Factory;


use Analysis\Service\MovingAverage;
use Course\Controller\IndexController;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ExchangeManager $exchangeManager */
        $exchangeManager = $container->get(ExchangeManager::class);
        /** @var CourseManager $courseManager */
        $courseManager = $container->get(CourseManager::class);
        /** @var MovingAverage $movingAverage */
        $movingAverage = $container->get(MovingAverage::class);

        return new IndexController($exchangeManager, $courseManager, $movingAverage);
    }

}
