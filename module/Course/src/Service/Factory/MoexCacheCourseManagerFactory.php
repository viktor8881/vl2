<?php
namespace Course\Service\Factory;

use Course\Entity\MoexCacheCourse;
use Course\Service\MoexCacheCourseManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexCacheCourseManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = MoexCacheCourse::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new MoexCacheCourseManager($entityManager, self::ENTITY_NAME);
        return $service;
    }

}