<?php
namespace Course\Service\Factory;

use Course\Entity\Course;
use Course\Service\CourseManager;
use Course\Service\CourseService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;


class CourseServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        /** @var CourseManager $courseManager */
        $courseManager = $container->get(CourseManager::class);

        $service = new CourseService($courseManager, $entityManager);
        return $service;
    }

}