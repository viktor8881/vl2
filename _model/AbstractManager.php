<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 01.01.2017
 * Time: 13:09
 */

namespace Model;


use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\LockMode;
use Zend\Paginator\Paginator;


abstract class AbstractManager implements IManager
{
    private $repository;

    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->repository->find($id, $lockMode, $lockVersion);
    }

    public function getByCriteria(CriteriaCollection $filters, OrderCollection $orders = null)
    {
        $criteria = $filters->toArray();
        $orderBy = $orders->toArray();
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    public function fetchAll(Paginator $paginator = null, OrderCollection $orders = null)
    {
        // TODO: Implement fetchAll() method.
    }

    public function fetchAllByCriteria(CriteriaCollection $criteria = null, Paginator $paginator = null, OrderCollection $orders = null)
    {
        // TODO: Implement fetchAllByCriteria() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }

    public function countByCriteria(CriteriaCollection $criteria = null)
    {
        // TODO: Implement countByCriteria() method.
    }

    public function insert(IEmpty $model, $flush = true)
    {
        // TODO: Implement insert() method.
    }

    public function update(IEmpty $model, $flush = true)
    {
        // TODO: Implement update() method.
    }

    public function delete(IEmpty $model, $flush = true)
    {
        // TODO: Implement delete() method.
    }

    public function createEntity(array $values = null)
    {
        // TODO: Implement createEntity() method.
    }


}