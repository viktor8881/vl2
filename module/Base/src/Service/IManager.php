<?php

namespace Base\Service;

use Base\Entity\CriterionCollection;
use Base\Entity\IEntity;
use Base\Entity\OrderCollection;
use Zend\Paginator\Paginator;

interface IManager
{

    public function get($id);

    public function getByCriterions(CriterionCollection $criteria = null,
        OrderCollection $orders = null
    );

    public function fetchAll(Paginator $paginator = null,
        OrderCollection $orders = null
    );

    public function fetchAllByCriterions(CriterionCollection $criteria = null,
        Paginator $paginator = null, OrderCollection $orders = null
    );

    public function count();

    public function countByCriterions(CriterionCollection $criteria = null);

    public function insert(IEntity $model);

    public function update(IEntity $model);

    public function delete(IEntity $model);

    public function createEntity(array $values = null);

}
