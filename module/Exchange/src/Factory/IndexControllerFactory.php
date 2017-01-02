<?php
namespace Exchange\Factory;


use Exchange\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
//        pr($container->get(\Exchange\Service\ExchangeManager::class)   ); exit;
        $serviceMetal = $container->get('ManagerExchange');
        $controller = new IndexController($serviceMetal);
        return $controller;
    }

}
