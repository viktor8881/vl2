<?php
namespace Course\Service\Factory;

use Course\Service\MoexCacheCourseManager;
use Course\Service\MoexCacheCourseService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexCacheCourseServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var MoexCacheCourseManager $courseManager */
        $courseManager = $container->get(MoexCacheCourseManager::class);
        $service = new MoexCacheCourseService($courseManager);
        return $service;
    }

}