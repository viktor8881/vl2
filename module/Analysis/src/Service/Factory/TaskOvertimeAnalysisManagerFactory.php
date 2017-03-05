<?php
namespace Analysis\Service\Factory;

use Analysis\Entity\TaskOvertimeAnalysis;
use Analysis\Service\TaskOvertimeAnalysisManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TaskOvertimeAnalysisManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = TaskOvertimeAnalysis::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new TaskOvertimeAnalysisManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}