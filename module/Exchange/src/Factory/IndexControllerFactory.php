<?php
namespace Exchange\Factory;


use Exchange\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceMetal = $container->get('ManagerExchange');
//        pr($serviceMetal); exit;
        $controller = new IndexController($serviceMetal);
        return $controller;
    }

}
