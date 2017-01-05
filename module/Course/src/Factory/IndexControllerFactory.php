<?php
namespace Course\Factory;


use Course\Controller\IndexController;
use Course\Service\CacheCourseManager;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $exchangeManager = $container->get(ExchangeManager::class);
        $cacheCourseManager = $container->get(CacheCourseManager::class);
        $controller = new IndexController($exchangeManager, $cacheCourseManager);
        return $controller;
    }

}
