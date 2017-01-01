<?php
namespace Model\Exchange;
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 19:06
 */

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;

class FactoryManager implements FactoryInterface
{
    const ENTITY_NAME = Entity::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $repository = $entityManager->getRepository(self::ENTITY_NAME);
        $service = new Manager($repository);
        return $service;
    }

}