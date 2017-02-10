<?php
namespace Investments\Factory;


use Investments\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Investments\Service\InvestmentsManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $investmentsManager = $container->get(InvestmentsManager::class);
        $controller = new IndexController($investmentsManager);
        return $controller;
    }

}