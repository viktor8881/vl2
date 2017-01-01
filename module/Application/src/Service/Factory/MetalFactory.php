<?php
namespace Application\Service\Factory;
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 19:06
 */

use Application\Service\Metal;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MetalFactory implements FactoryInterface
{
    const ENTITY_NAME = '\Application\Entity\Metal';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**  @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $service = new Metal($entityManager, self::ENTITY_NAME);
        return $service;
    }

}