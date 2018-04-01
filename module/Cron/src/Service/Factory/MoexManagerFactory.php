<?php
namespace Cron\Service\Factory;

use Cron\Entity\Moex;
use Cron\Service\MoexManager;
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