<?php
namespace Analysis\Service\Factory;

use Analysis\Service\MoexFigureAnalysisManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexFigureAnalysisManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = MoexFigureAnalysis::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new MoexFigureAnalysisManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}