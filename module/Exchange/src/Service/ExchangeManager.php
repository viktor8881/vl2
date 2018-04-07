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
use Base\Service\AbstractManager;
use Course\Service\MoexService;
use Doctrine\ORM\QueryBuilder;
use Exchange\Entity\Criterion\ExchangeId;
use Exchange\Entity\Criterion\ExchangeNotCode;
use Exchange\Entity\Criterion\ExchangeType;
use Exchange\Entity\Exchange;

class ExchangeManager extends AbstractManager
{

    const MAP_MOEX_SECID = [
        MoexService::USD_SEC_ID => 5,
        MoexService::EUR_SEC_ID => 8
    ];

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
        if (!isset(self::MAP_MOEX_SECID[$secId])) {
            return null;
        }
        return $this->get(self::MAP_MOEX_SECID[$secId]);
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
                $qb->andWhere($this->entityName . '.type IN (:type_id)')
                    ->setParameter('type_id', $criterion->getValues());
                break;
            case ExchangeNotCode::class:
                $qb->andWhere($this->entityName . '.code NOT IN (:code)')
                    ->setParameter('code', $criterion->getValues());
                break;
            default:
                break;
        }
    }

    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {
//        switch (get_class($order)) {
//            case 'Question_Order_Status':
//                $result = $prefix.'.status '.$order->getTypeOrder();
//                break;
//            default:
//                break;
//        }
    }


}