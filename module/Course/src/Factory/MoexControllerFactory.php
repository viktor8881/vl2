<?php

namespace Course\Factory;


use Course\Controller\MoexController;
use Course\Service\MoexService;
use Exchange\Service\ExchangeManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MoexControllerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        /** @var ExchangeManager $exchangeManager */
        $exchangeManager = $container->get(ExchangeManager::class);
        /** @var MoexService $moexService */
        $moexService = $container->get(MoexService::class);

        return new MoexController($moexService, $exchangeManager);
    }

}
