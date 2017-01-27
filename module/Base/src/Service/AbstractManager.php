<?php

namespace Base\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\IEmpty;
use Base\Entity\OrderCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Zend\Paginator\Paginator;

abstract class AbstractManager implements IManager
{
    protected $em;
    protected $entityName;

    public function __construct(EntityManager $em, $entityName)
    {
        $this->em = $em;
        $this->entityName = $entityName;
    }

    public function get($id)
    {
        return $this->em->getRepository($this->entityName)->find($id);
    }

    public function getByCriterons(CriterionCollection $criterions = null,
        OrderCollection $orders = null
    ) {
        return $this->em->getRepository($this->entityName)->findOneBy(
            $criterions->toArray(), $orders->toArray()
        );
    }

    public function fetchAll(Paginator $paginator = null,
        OrderCollection $orders = null
    ) {
        return $this->fetchAllByCriterions(null, $paginator, $orders);
    }

    public function fetchAllByCriterions(CriterionCollection $criterions = null,
        Paginator $paginator = null, OrderCollection $orders = null
    ) {
        $qb = $this->em->createQueryBuilder();
        $qb->select($this->entityName)
            ->from($this->entityName, $this->entityName);
        if ($criterions) {
            $this->addCriterions($criterions, $qb);
        }
        $orderBy = null;
        if ($orders) {
            $this->addOrders($orders, $qb);
        }
        if ($paginator) {
            $qb->setFirstResult(
                ($paginator->getCurrentPageNumber() - 1)
                * $paginator->getItemCountPerPage()
            )
                ->setMaxResults($paginator->getItemCountPerPage());
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function count()
    {
        return $this->countByCriteria();
    }

    public function countByCriteria(CriterionCollection $criterions = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(' . $this->entityName . '.id) as count_item')
            ->from($this->entityName, $this->entityName);
        if ($criterions) {
            $this->addCriterions($criterions, $qb);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function insert(IEmpty $model)
    {
        $this->em->persist($model);
        $this->em->flush();
        return $model;
    }

    public function update(IEmpty $model)
    {
        $this->em->persist($model);
        $this->em->flush();
        return $this;
    }

    public function delete(IEmpty $model)
    {
        $this->em->remove($model);
        $this->em->flush();
        return $this;
    }

    public function createEntity(array $values = null)
    {
        return new $this->entityName($values);
    }

    protected function addCriterions(CriterionCollection $criterions = null,
        QueryBuilder $qb
    ) {
        foreach ($criterions as $criterion) {
            if ($criterion->countValue()) {
                $this->addCriterion($criterion, $qb);
            }
        }
    }

    protected function addOrders(OrderCollection $orders = null, QueryBuilder $qb)
    {
        foreach ($orders as $order) {
            $this->addOrder($order, $qb);
        }
    }

    abstract protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    );

    abstract protected function addOrder(AbstractOrder $order,
        QueryBuilder $qb
    );

}