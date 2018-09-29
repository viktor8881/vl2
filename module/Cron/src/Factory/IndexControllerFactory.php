<?php
namespace Cron\Factory;


use Base\Queue\Adapter\Doctrine\Service\QueueManager;
use Base\Queue\Adapter\DoctrineAdapter;
use Base\Queue\Queue;
use Cron\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var QueueManager $queueManager */
        $queueManager = $container->get(QueueManager::class);
        $values['options'][DoctrineAdapter::MANAGER_NAME] = $queueManager;

        $doctrineAdapter = new DoctrineAdapter($values);
//
//        // =============================================================================================================
//        $queues = new QueueCollection();
//        $queues->setQueueAdapter($doctrineAdapter);
//
//        $queue = $queues->getByName('moex-receive');
//        $queue->sendArray(['exchangeId' => 1]);
//
//        $queue = $queues->getByName('moex-receive2');
//        $queue->sendArray(['exchangeId' => 2]);
//
//        $queue = $queues->getByName('moex-receive3');
//        $queue->sendArray(['exchangeId' => 3]);
//        exit;

        // =============================================================================================================

        $options = ['name' => 'def_queue'];
        $queue = new Queue($doctrineAdapter, $options);

        $options = ['name' => 'moex'];
        $moexQueue = new Queue($doctrineAdapter, $options);

        return new IndexController($queue, $moexQueue);
    }

}