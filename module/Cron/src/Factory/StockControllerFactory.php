<?php
namespace Cron\Factory;

use Course\Service\MoexService;
use Cron\Controller\StockController;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class StockControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $moexService = $container->get(MoexService::class);
        return new StockController($exchangeManager, $moexService);
    }

}