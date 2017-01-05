<?php
namespace Course\Service\Factory;

use Course\Entity\Course;
use Course\Service\CourseManager;
use Interop\Container\ContainerInterface;

use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;


class CourseManagerFactory implements FactoryInterface
{
    const ENTITY_NAME = Course::class;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $repository = $entityManager->getRepository(Course::class);
        $service = new CourseManager($repository);
        return $service;
    }

}