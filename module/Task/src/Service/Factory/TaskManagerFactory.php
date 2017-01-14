<?php
namespace Task\Service\Factory;

use Task\Entity\Task;
use Task\Service\TaskManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;



class TaskManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Task::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new TaskManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}