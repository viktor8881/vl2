<?php
namespace Account\Service\Factory;

use Account\Entity\Account;
use Account\Service\AccountManager;
use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;


class AccountManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Account::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new AccountManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}