<?php
namespace Cron\Factory;

use Base\Queue\Adapter\Doctrine;
use Base\Queue\Adapter\Doctrine\Service\QueueManager;
use Cron\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZendQueue\Queue;


class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var QueueManager $queueManager */
        $queueManager = $container->get(QueueManager::class);
        $values['options'][Doctrine::MANAGER_NAME] = $queueManager;
        $doctrineAdapter = new Doctrine($values);
        $options = ['name' => 'def_queue'];
        $queue = new Queue($doctrineAdapter, $options);
        $controller = new IndexController($queue);
        return $controller;

    }

}