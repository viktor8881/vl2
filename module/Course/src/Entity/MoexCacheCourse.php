<?php

namespace Course\Entity;

use Base\Entity\AbstractEntity;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="moex_cache_course")
 */
class MoexCacheCourse extends AbstractEntity
{

    const TREND_UP = 1;
    const TREND_DOWN = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Exchange\Entity\Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     * @var Exchange
     */
    protected $exchange;

    /** @ORM\Column(name="type_trend", type="string", length=1) */
    protected $typeTrend;

    /** @ORM\Column(name="data_value", type="json_array") */
    protected $dataValue = array();

    /** @ORM\Column(name="last_value", type="decimal", precision=6, scale=20) */
    protected $lastValue;

    /** @ORM\Column(name="last_date", type="datetime") */
    protected $lastDate;

    /** @ORM\Column(name="percent", type="decimal", precision=2, scale=4) */
    protected $percent;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CacheCourse
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Exchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @param Exchange $exchange
     * @return CacheCourse
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeTrend()
    {
        return $this->typeTrend;
    }

    /**
     * @param int $typeTrend
     * @return CacheCourse
     */
    public function setTypeTrend($typeTrend)
    {
        $this->typeTrend = $typeTrend;
        return $this;
    }

    /**
     * @return array
     */
    public function getDataValue()
    {
        return $this->dataValue;
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstDate()
    {
        $data = $this->getDataValue();
        $first = reset($data);
        if ($first) {
            return new \DateTime($first['data']);
        }
        return null;
    }

    public function getFirstValue() {
        $data = $this->getDataValue();
        $first = reset($data);
        if ($first) {
            return $first['value'];
        }
        return null;
    }

    /**
     * @param array $dataValue
     * @return CacheCourse
     */
    public function setDataValue($dataValue)
    {
        $this->dataValue = $dataValue;
        return $this;
    }

    /**
     * @return int
     */
    public function countDataValue()
    {
        return count($this->dataValue);
    }

    /**
     * @param \DateTime $date
     * @param           $value
     * @return $this
     */
    public function addDataValue(\DateTime $date,  $value) {
        $this->dataValue[] = array('data' => $date->format('d.m.Y H:i:s'), 'value' => $value);
        return $this;
    }

    /**
     * @param Moex $model
     * @return CacheCourse
     */
    public function addDataValueByCourse(Moex $model) {
        return $this->addDataValue($model->getDate(), $model->getValue());
    }

    /**
     * @return float
     */
    public function getLastValue()
    {
        return $this->lastValue;
    }

    /**
     * @param float $lastValue
     * @return CacheCourse
     */
    public function setLastValue($lastValue)
    {
        $this->lastValue = $lastValue;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastDate()
    {
        return $this->lastDate;
    }

    /**
     * @param \DateTime $lastDate
     * @return CacheCourse
     */
    public function setLastDate($lastDate)
    {
        $this->lastDate = $lastDate;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     * @return CacheCourse
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMetal()
    {
        return $this->getExchange()->isMetal();
    }

    /**
     * @return bool
     */
    public function isCurrency()
    {
        return $this->getExchange()->isCurrency();
    }

        public function isUpTrend() {
        return $this->getTypeTrend() == self::TREND_UP;
    }

    public function isDownTrend() {
        return $this->getTypeTrend() == self::TREND_DOWN;
    }

}