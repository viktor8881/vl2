<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Application\Service;

use Doctrine\ORM\EntityManager;

class Metal
{

    private $entityInvoked;
    private $em;

    public function __construct(EntityManager $em, $entityInvokable)
    {
        $this->setEntityInvoked($entityInvokable)
            ->setEm($em);
    }

    public function fetchAll()
    {
        return $this->getEm()->getRepository($this->entityInvoked)->findAll();
    }

    public function createEntity(array $values = [])
    {
        return new $this->entityInvoked($values);
    }


    //==================================================================================================================

    /**
     * @return mixed
     */
    public function getEntityInvoked()
    {
        return $this->entityInvoked;
    }

    /**
     * @param mixed $entityInvoked
     * @return Metal
     */
    private function setEntityInvoked($entityInvoked)
    {
        $this->entityInvoked = $entityInvoked;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     * @return Metal
     */
    private function setEm($em)
    {
        $this->em = $em;
        return $this;
    }



}