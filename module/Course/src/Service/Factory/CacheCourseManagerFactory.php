<?php
namespace Course\Service\Factory;

use Course\Entity\CacheCourse;
use Course\Service\CacheCourseManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class CacheCourseManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = CacheCourse::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new CacheCourseManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}