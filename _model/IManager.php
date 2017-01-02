<?php

namespace Model;

use Doctrine\DBAL\LockMode;
use Zend\Paginator\Paginator;

interface IManager
{

    public function get($id, $lockMode = LockMode::NONE, $lockVersion = null);

    public function getByCriteria(CriteriaCollection $criteria, OrderCollection $orders = null);

    public function fetchAll(Paginator $paginator = null, OrderCollection $orders = null);

    public function fetchAllByCriteria(CriteriaCollection $criteria = null, Paginator $paginator = null, OrderCollection $orders = null);

    public function count();

    public function countByCriteria(CriteriaCollection $criteria = null);

    public function insert(IEmpty $model, $flush = true);

    public function update(IEmpty $model, $flush = true);

    public function delete(IEmpty $model, $flush = true);

    public function createEntity(array $values = null);

}
