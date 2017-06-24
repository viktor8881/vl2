<?php
namespace Cron\Service\Factory;

use Analysis\Service\MovingAverage;
use Cron\Service\ServiceExp1;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class ServiceExp1Factory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $movingAverage = $container->get(MovingAverage::class);
        return new ServiceExp1($movingAverage);
    }

}