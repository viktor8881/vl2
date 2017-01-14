<?php
namespace Task\Factory;

use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Task\Controller\PercentController;
use Zend\ServiceManager\Factory\FactoryInterface;

class PercentControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $controller = new PercentController($exchangeManager);
        return $controller;
    }

}
