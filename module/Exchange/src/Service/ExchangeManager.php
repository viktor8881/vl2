<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Exchange\Service;


use Core\Service\AbstractManager;
use Doctrine\ORM\EntityRepository;
use Exchange\Entity\Exchange;

class ExchangeManager
{

    private $repositoryEntity;

    public function __construct(EntityRepository $repositoryEntity)
    {
        $this->repositoryEntity = $repositoryEntity;
    }

    public function get($id)
    {
        return $this->repositoryEntity->find($id);
    }

    public function getMetalById($id)
    {
        $item = $this->get($id);
        if ($item && $item->isMetal()) {
            return $item;
        }
        return null;
    }

    public function getCurrencyById($id)
    {
        $item = $this->get($id);
        if ($item && $item->isCurrency()) {
            return $item;
        }
        return null;
    }

    public function fetchAllMetal()
    {
        return $this->repositoryEntity->findBy(['type' => Exchange::TYPE_METAl]);
    }

    public function fetchAllCurrency()
    {
        return $this->repositoryEntity->findBy(['type' => Exchange::TYPE_CURRENCY]);
    }

}