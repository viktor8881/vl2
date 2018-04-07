<?php
namespace Course\Service\Factory;

use Course\Entity\Moex;
use Course\Service\MoexManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Moex::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new MoexManager($entityManager, self::ENTITY_NAME);
    }

}