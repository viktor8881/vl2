<?php
namespace Analysis\Service\Factory;

use Analysis\Entity\FigureAnalysis;
use Analysis\Service\FigureAnalysisManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class FigureAnalysisManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = FigureAnalysis::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new FigureAnalysisManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}