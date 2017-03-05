<?php
namespace Task\Service\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Task\Entity\TaskPercent;
use Task\Service\TaskPercentManager;
use Zend\ServiceManager\Factory\FactoryInterface;


class TaskPercentManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = TaskPercent::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new TaskPercentManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}