<?php
namespace Base\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;


class LoggerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('config')['logger'];
        return new Logger($options);
    }

}