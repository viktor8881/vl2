<?php
namespace Exchange\Service\Factory;

use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;


class ExchangeManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Exchange::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $repository = $entityManager->getRepository(Exchange::class);
        $service = new ExchangeManager($repository);
        return $service;
    }

}