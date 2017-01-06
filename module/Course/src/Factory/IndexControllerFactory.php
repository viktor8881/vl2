<?php
namespace Course\Factory;


use Course\Controller\IndexController;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $courseManager = $container->get(CourseManager::class);
        $controller = new IndexController($exchangeManager, $courseManager);
        return $controller;
    }

}
