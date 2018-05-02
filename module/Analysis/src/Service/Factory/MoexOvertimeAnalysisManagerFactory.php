<?php
namespace Analysis\Service\Factory;

use Analysis\Entity\MoexOvertimeAnalysis;
use Analysis\Service\MoexOvertimeAnalysisManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MoexOvertimeAnalysisManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = MoexOvertimeAnalysis::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new MoexOvertimeAnalysisManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}