<?php
namespace Exchange\Service\Factory;

use Doctrine\ORM\EntityManager;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class ExchangeManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Exchange::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new ExchangeManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}