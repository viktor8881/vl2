<?php
namespace Task\Factory;


use Task\Controller\IndexController;
use Task\Service\TaskManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskManager = $container->get(TaskManager::class);
        $controller = new IndexController($taskManager);
        return $controller;
    }

}
