<?php
namespace Course\Service\Factory;

use Course\Service\MoexManager;
use Course\Service\MoexService;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $moexManager = $container->get(MoexManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);
        return new MoexService($moexManager, $exchangeManager);
    }

}