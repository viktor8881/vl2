<?php
namespace Cron\Factory;


use Base\Queue\Adapter\Doctrine\Service\QueueManager;
use Base\Queue\Adapter\DoctrineAdapter;
use Base\Queue\Queue;
use Cron\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;


class IndexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var QueueManager $queueManager */
        $queueManager = $container->get(QueueManager::class);
        $values['options'][DoctrineAdapter::MANAGER_NAME] = $queueManager;

        $doctrineAdapter = new DoctrineAdapter($values);

        $options = ['name' => 'def_queue'];
        $queue = new Queue($doctrineAdapter, $options);

        $options = ['name' => 'moex'];
        $moexQueue = new Queue($doctrineAdapter, $options);

        $config = $container->get('config');
        $logger = new Logger($config['logger']);

        return new IndexController($queue, $moexQueue, $logger);
    }

}