<?php
namespace Task\Service\Factory;

use Task\Entity\TaskOvertime;
use Interop\Container\ContainerInterface;
use Task\Service\TaskOvertimeManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;



class TaskOvertimeManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = TaskOvertime::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new TaskOvertimeManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}