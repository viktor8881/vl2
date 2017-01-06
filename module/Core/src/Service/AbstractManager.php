<?php

namespace Core\Service;

use Core\Entity\AbstractCriterion;
use Core\Entity\AbstractOrder;
use Core\Entity\CriterionCollection;
use Core\Entity\IEmpty;
use Core\Entity\OrderCollection;
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

    public function getByCriteria(CriterionCollection $criterions = null,
        OrderCollection $orders = null
    ) {
        return $this->em->getRepository($this->entityName)->findOneBy(
            $criterions->toArray(), $orders->toArray()
        );
    }

    public function fetchAll(Paginator $paginator = null,
        OrderCollection $orders = null
    ) {
        $limit = ($paginator->getCurrentPageNumber() - 1)
            * $paginator->getItemCountPerPage();
        $offset = $paginator->getItemCountPerPage();
        return $this->em->getRepository($this->entityName)->findBy(
            [], $orders->toArray(), $limit, $offset
        );
    }

    public function fetchAllByCriteria(CriterionCollection $criterions = null, Paginator $paginator = null, OrderCollection $orders = null )
    {
        $qb = $this->em->createQueryBuilder();//('SELECT '.$this->entityName.' FROM '.$this->entityName.' '.$this->entityName);
        $qb->select($this->entityName)
            ->from($this->entityName, $this->entityName);
        if ($criterions) {
            $this->criterionsToString($criterions, $qb);
        }
        $orderBy=null;
        if ($orders) {
            $this->ordersToString($orders, $qb);
        }
        if ($paginator){
            $qb->setFirstResult(($paginator->getCurrentPageNumber()-1)* $paginator->getItemCountPerPage())
                ->setMaxResults($paginator->getItemCountPerPage());
        }
        $query = $qb->getQuery();
        return $query->getResult();

//
//        $limit = null;
//        $offset = null;
//        if ($paginator) {
//            $limit = ($paginator->getCurrentPageNumber() - 1)
//                * $paginator->getItemCountPerPage();
//            $offset = $paginator->getItemCountPerPage();
//        }
////        pr($criterions->toArray()); exit;
//        return $this->em->getRepository($this->entityName)->findBy(
//            $this->criteriaToArray($criterions), $this->ordersToArray($orders), $limit, $offset
//        );
    }

    public function count()
    {
        $query = $this->em->createQuery(
            'SELECT COUNT(' . $this->entityName . '.id) FROM '
            . $this->entityName . ' ' . $this->entityName
        );
        return $query->getSingleScalarResult();
    }

    public function countByCriteria(CriterionCollection $criterions = null)
    {
        $whereBy = null;
//        if ($filters) {
//            $whereBy = ' WHERE '.$this->_conditionByFilters($filters);
//        }
        $query = $this->em->createQuery(
            'SELECT COUNT(' . $this->entityName . '.id) FROM '
            . $this->entityName . ' ' . $this->entityName . ' ' . $whereBy
        );
        return $query->getSingleScalarResult();
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

    protected function criterionsToString(CriterionCollection $criterions = null, QueryBuilder $qb)
    {
        $result = array();
        foreach ($criterions as $criterion) {
            if ($criterion->countValue()) {
                $result[] = $this->criterionToString($criterion, $qb);
            }
        }
        return implode(' AND ', $result);
    }

    protected function ordersToString(OrderCollection $orders = null)
    {
        $result = array();
        foreach ($orders as $order) {
            $result[] = $this->orderToString($order, $qb);
        }
        return implode(',', $result);
    }

    abstract protected function criterionToString(AbstractCriterion $criterion, QueryBuilder $qb);
    abstract protected function orderToString(AbstractOrder $order, QueryBuilder $qb);

}