<?php
namespace Task\Factory;


use Interop\Container\ContainerInterface;
use Task\Controller\IndexController;
use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taskPercentManager = $container->get(TaskPercentManager::class);
        $taskOvertimeManager = $container->get(TaskOvertimeManager::class);
        $controller = new IndexController($taskPercentManager, $taskOvertimeManager);
        return $controller;
    }

}
