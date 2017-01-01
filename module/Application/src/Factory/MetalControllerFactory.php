<?php
namespace Application\Factory;


use Application\Controller\MetalController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Model;

class MetalControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceMetal = $container->get('ServiceMetal');
        $controller = new MetalController($serviceMetal);
        return $controller;
    }

}
