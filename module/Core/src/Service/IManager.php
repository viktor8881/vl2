<?php

namespace Core\Service;

use Core\Entity\CriterionCollection;
use Core\Entity\IEmpty;
use Core\Entity\OrderCollection;
use Zend\Paginator\Paginator;

interface IManager
{

    public function get($id);

    public function getByCriteria(CriterionCollection $criteria = null, OrderCollection $orders  = null);

    public function fetchAll(Paginator $paginator = null, OrderCollection $orders  = null);

    public function fetchAllByCriteria(CriterionCollection $criteria = null, Paginator $paginator = null, OrderCollection $orders  = null);

    public function count();

    public function countByCriteria(CriterionCollection $criteria = null);

    public function insert(IEmpty $model);

    public function update(IEmpty $model);

    public function delete(IEmpty $model);

    public function createEntity(array $values = null);

}
