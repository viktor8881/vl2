<?php
namespace Base\Service\Factory;

use Base\Service\JpGraphService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class JpGraphServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('config')['jpgraph'];

        return new JpGraphService($options['folderImgs'], $options['publicPath']);
    }

}