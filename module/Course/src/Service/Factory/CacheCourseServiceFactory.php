<?php
namespace Course\Service\Factory;

use Course\Service\CacheCourseManager;
use Course\Service\CacheCourseService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class CacheCourseServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var CacheCourseManager $courseManager */
        $courseManager = $container->get(CacheCourseManager::class);
        $service = new CacheCourseService($courseManager);
        return $service;
    }

}