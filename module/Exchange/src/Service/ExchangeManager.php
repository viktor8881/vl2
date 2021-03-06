<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Exchange\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Entity\OrderCollection;
use Base\Service\AbstractManager;
use Course\Service\MoexService;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Criterion\ExchangeFavorite;
use Exchange\Entity\Criterion\ExchangeId;
use Exchange\Entity\Criterion\ExchangeNotCode;
use Exchange\Entity\Criterion\ExchangeType;
use Exchange\Entity\Criterion\MoexSecId;
use Exchange\Entity\Exchange;
use Exchange\Entity\Order\OrderName;
use Zend\Paginator\Adapter\NullFill;
use Zend\Paginator\Factory;

class ExchangeManager extends AbstractManager
{

    const COUNT_FAVORITE_PER_PAGE = 5;

    const MAP_MOEX_SECID = [
        MoexService::GOLD_SEC_ID => 1,
        MoexService::USD_SEC_ID => 5,
        MoexService::EUR_SEC_ID => 8
    ];

    /**
     * @return array
     */
    public function listExchangeId()
    {
        $list = [];
        foreach ($this->fetchAll() as $entity) {
            $list[] = $entity->getId();
        }
        return $list;
    }

    /**
     * @return Exchange[]
     */
    public function fetchAllIndexCode() {
        $result = [];
        /** @var Exchange $exchange */
        foreach ($this->fetchAll() as $exchange) {
            $result[$exchange->getCode()] = $exchange;
        }
        return $result;
    }

    /**
     * @return Exchange[]
     */
    public function fetchAllIndexId()
    {
        $result = [];
        foreach ($this->fetchAll() as $exchange) {
            $result[$exchange->getId()] = $exchange;
        }
        return $result;
    }

    /**
     * @return Exchange[]
     */
    public function fetchAllMoex()
    {
        return $this->fetchAllByListId(array_values(self::MAP_MOEX_SECID));
    }

    /**
     * @param int[] $list
     * @return Exchange[]
     */
    public function fetchAllByListId(array $list)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeId($list));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param $id
     * @return Exchange|null
     */
    public function getMetalById($id)
    {
        /** @var Exchange $item */
        $item = $this->get($id);
        if ($item && $item->isMetal()) {
            return $item;
        }
        return null;
    }

    /**
     * @param $id
     * @return Exchange|null
     */
    public function getStockById($id)
    {
        /** @var Exchange $item */
        $item = $this->get($id);
        if ($item && $item->isStock()) {
            return $item;
        }
        return null;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getCurrencyById($id)
    {
        $item = $this->get($id);
        if ($item && $item->isCurrency()) {
            return $item;
        }
        return null;
    }

    /**
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllMetal()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeType(Exchange::TYPE_METAl));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllCurrency()
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeType(Exchange::TYPE_CURRENCY));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllStock(OrderCollection $order = null)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeType(Exchange::TYPE_STOCK));
        return $this->fetchAllByCriterions($criterions, null, $order);
    }

    /**
     * @param int $page
     * @param OrderCollection|null $order
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllFavorite(int $page = null,  OrderCollection $order = null)
    {
        if ($page) {
            $paginator = Factory::factory(100, new NullFill());
            $paginator->setItemCountPerPage(self::COUNT_FAVORITE_PER_PAGE);
            $paginator->setCurrentPageNumber($page);
        } else {
            $paginator = null;
        }

        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeFavorite(true));
        return $this->fetchAllByCriterions($criterions, $paginator, $order);
    }

    /**
     * @param array $codes
     * @return \Base\Entity\AbstractEntity[]
     */
    public function fetchAllExceptCode(array $codes)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new ExchangeNotCode($codes));
        return $this->fetchAllByCriterions($criterions);
    }

    /**
     * @param string $secId
     * @return null|object
     */
    public function getByMoexSecid($secId)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new MoexSecId($secId));
        return $this->getByCriterions($criterions);
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder      $qb
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb)
    {
        switch (get_class($criterion)) {
            case ExchangeId::class:
                $qb->andWhere($this->entityName . '.id IN (:id)')
                    ->setParameter('id', $criterion->getValues());
                break;
            case ExchangeType::class:
                $qb->andWhere($this->entityName . '.type IN (:type)')
                    ->setParameter('type', $criterion->getValues());
                break;
            case ExchangeFavorite::class:
                $qb->andWhere($this->entityName . '.favorite = :favorite')
                    ->setParameter('favorite', $criterion->getValues());
                break;
            case ExchangeNotCode::class:
                $qb->andWhere($this->entityName . '.code NOT IN (:code)')
                    ->setParameter('code', $criterion->getValues());
                break;
            case MoexSecId::class:
                $qb->andWhere($this->entityName . '.moexSecId IN (:moexSecId)')
                    ->setParameter('moexSecId', $criterion->getValues());
                break;
            default:
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
        switch (get_class($order)) {
            case OrderName::class:
                $qb->orderBy($this->entityName.'.name', $order->getTypeOrder());
                break;
            default:
                break;
        }
    }

}