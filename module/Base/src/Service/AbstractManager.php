<?php

namespace Base\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractEntity;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\IEmpty;
use Base\Entity\OrderCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Zend\Paginator\Paginator;

/**
 * Class AbstractManager
 *
 * @package Base\Service
 */
abstract class AbstractManager implements IManager
{
    /** @var EntityManager */
    protected $em;

    /** @var string */
    protected $entityName;

    /**
     * AbstractManager constructor.
     *
     * @param EntityManager $em
     * @param               $entityName
     */
    public function __construct(EntityManager $em, $entityName)
    {
        $this->em = $em;
        $this->entityName = $entityName;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param $id
     * @return AbstractEntity|null
     */
    public function get($id)
    {
        return $this->em->getRepository($this->entityName)->find($id);
    }

    /**
     * @param CriterionCollection|null $criterions
     * @param OrderCollection|null     $orders
     *
     * @return AbstractEntity|null
     */
    public function getByCriterions(CriterionCollection $criterions = null,
        OrderCollection $orders = null
    ) {
        $qb = $this->em->createQueryBuilder();
        $qb->select($this->entityName)
            ->from($this->entityName, $this->entityName);

        $this->addCriterions($criterions, $qb);
        $this->addOrders($orders, $qb);

        $qb->setFirstResult(0)
            ->setMaxResults(1);

        $query = $qb->getQuery();
        return $query->getSingleResult();
    }

    /**
     * @param Paginator|null       $paginator
     * @param OrderCollection|null $orders
     *
     * @return AbstractEntity[]
     */
    public function fetchAll(Paginator $paginator = null,
        OrderCollection $orders = null
    ) {
        return $this->fetchAllByCriterions(null, $paginator, $orders);
    }

    /**
     * @param CriterionCollection|null $criterions
     * @param Paginator|null           $paginator
     * @param OrderCollection|null     $orders
     *
     * @return AbstractEntity[]
     */
    public function fetchAllByCriterions(CriterionCollection $criterions = null,
        Paginator $paginator = null, OrderCollection $orders = null
    ) {
        $qb = $this->em->createQueryBuilder();
        $qb->select($this->entityName)
            ->from($this->entityName, $this->entityName);

        $this->addCriterions($criterions, $qb);
        $this->addOrders($orders, $qb);

        if ($paginator) {
            $qb->setFirstResult(($paginator->getCurrentPageNumber() - 1) * $paginator->getItemCountPerPage())
                ->setMaxResults($paginator->getItemCountPerPage());
        }
//        pr($qb->getDQL()); exit;
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * @param CriterionCollection|null $criterions
     * @param QueryBuilder             $qb
     */
    protected function addCriterions(CriterionCollection $criterions = null,
        QueryBuilder $qb
    ) {
        if ($criterions) {
            foreach ($criterions as $criterion) {
                $this->addCriterion($criterion, $qb);
            }
        }
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     *
     * @return mixed
     */
    abstract protected function addCriterion(AbstractCriterion $criterion,
        QueryBuilder $qb
    );

    /**
     * @param OrderCollection|null $orders
     * @param QueryBuilder         $qb
     */
    protected function addOrders(OrderCollection $orders = null,
        QueryBuilder $qb
    ) {
        if ($orders) {
            foreach ($orders as $order) {
                $this->addOrder($order, $qb);
            }
        }
    }

    /**
     * @param AbstractOrder $order
     * @param QueryBuilder  $qb
     * @return mixed
     */
    abstract protected function addOrder(AbstractOrder $order, QueryBuilder $qb);

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->countByCriterions();
    }

    /**
     * @param CriterionCollection|null $criterions
     *
     * @return mixed
     */
    public function countByCriterions(CriterionCollection $criterions = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(' . $this->entityName . '.id) as count_item')
            ->from($this->entityName, $this->entityName);
        if ($criterions) {
            $this->addCriterions($criterions, $qb);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param IEmpty $model
     *
     * @return IEmpty
     */
    public function insert(IEmpty $model)
    {
        $this->em->persist($model);
        $this->em->flush();
        return $model;
    }

    /**
     * @param IEmpty $model
     *
     * @return $this
     */
    public function update(IEmpty $model)
    {
        $this->em->persist($model);
        $this->em->flush();
        return $this;
    }

    /**
     * @param IEmpty $model
     *
     * @return $this
     */
    public function delete(IEmpty $model)
    {
        $this->em->remove($model);
        $this->em->flush();
        return $this;
    }

    /**
     * @param array|null $values
     *
     * @return mixed
     */
    public function createEntity(array $values = null)
    {
        return new $this->entityName($values);
    }

}