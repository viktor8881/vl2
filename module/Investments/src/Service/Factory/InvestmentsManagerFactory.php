<?php
namespace Investments\Service\Factory;

use Investments\Entity\Investments;
use Investments\Service\InvestmentsManager;
use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;


class InvestmentsManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Investments::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new InvestmentsManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}