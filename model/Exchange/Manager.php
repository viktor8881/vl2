<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Model\Exchange;



use Doctrine\ORM\EntityRepository;
use Model\AbstractManager;

class Manager extends AbstractManager
{

    private $repositoryEntity;

    public function __construct(EntityRepository $repositoryEntity)
    {
        $this->repositoryEntity = $repositoryEntity;
    }

    public function fetchAllMetal()
    {
        return $this->repositoryEntity->findBy(['type' => Entity::TYPE_METAl]);
    }

    public function fetchAllCurrency()
    {
        return $this->repositoryEntity->findBy(['type' => Entity::TYPE_CURRENCY]);
    }

}