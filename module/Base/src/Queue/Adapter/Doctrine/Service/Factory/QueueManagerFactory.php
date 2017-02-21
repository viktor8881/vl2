<?php
namespace Base\Queue\Adapter\Doctrine\Service\Factory;

use Base\Queue\Adapter\Doctrine\Entity\Queue;
use Base\Queue\Adapter\Doctrine\Service\QueueManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class QueueManagerFactory implements FactoryInterface
{

//    const ENTITY_NAME = \Base\Entity\Queue::class;
    const ENTITY_NAME = Queue::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new QueueManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}