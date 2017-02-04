<?php
namespace Account\Factory;


use Account\Controller\IndexController;
use Account\Service\AccountManager;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $accountManager = $container->get(AccountManager::class);
        $exchangeManager = $container->get(ExchangeManager::class);
        $controller = new IndexController($accountManager, $exchangeManager);
        return $controller;
    }

}
