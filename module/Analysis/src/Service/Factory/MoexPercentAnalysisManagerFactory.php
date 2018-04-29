<?php
namespace Analysis\Service\Factory;

use Analysis\Entity\MoexPercentAnalysis;
use Analysis\Service\MoexPercentAnalysisManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexPercentAnalysisManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = MoexPercentAnalysis::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new MoexPercentAnalysisManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}