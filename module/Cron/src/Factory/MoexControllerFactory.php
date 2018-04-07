<?php
namespace Cron\Factory;

use Cron\Controller\MoexController;
use Course\Service\MoexService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class MoexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $moexService = $container->get(MoexService::class);
        return new MoexController($moexService);
    }

}