<?php
namespace Analysis\Service\Factory;

use Analysis\Entity\TaskPercentAnalysis;
use Analysis\Service\TaskPercentAnalysisManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class TaskPercentAnalysisManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = TaskPercentAnalysis::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new TaskPercentAnalysisManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}