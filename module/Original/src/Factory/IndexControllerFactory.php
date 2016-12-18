<?php
namespace Application\Factory;


use Application\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
//        var_dump($container);
        $dateNow = new \DateTime();
        $controller = new IndexController($dateNow);
        return $controller;
    }

}
