<?php
namespace Course\Entity;

use Base\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 */
class Course extends AbstractEntity implements ICourse
{

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

    /** @ORM\Column(name="nominal", type="integer") */
    protected $nominal;

    /** @ORM\Column(name="buy", type="decimal", precision=6, scale=20) */
    protected $buy;

    /** @ORM\Column(name="sell", type="decimal", precision=6, scale=20) */
    protected $sell;

    /** @ORM\Column(name="date_create", type="date") */
    protected $dateCreate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Course
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
     * @return integer|null
     */
    public function getExchangeId()
    {
        $exchange = $this->exchange;
        return  ($exchange) ? $exchange->getId() : null;
    }

    /**
     * @param Exchange $exchange
     * @return $this
     */
    public function setExchange(Exchange $exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return integer
     */
    public function getNominal()
    {
        return $this->nominal;
    }

    /**
     * @param integer $nominal
     * @return Course
     */
    public function setNominal($nominal)
    {
        $this->nominal = $nominal;
        return $this;
    }

    /**
     * @return float
     */
    public function getBuy()
    {
        return $this->buy;
    }

    /**
     * @param float $buy
     * @return $this
     */
    public function setBuy($buy)
    {
        $this->buy = $buy;
        return $this;
    }

    /**
     * @return float
     */
    public function getSell()
    {
        return $this->sell;
    }

    /**
     * @param float $sell
     * @return $this
     */
    public function setSell($sell)
    {
        $this->sell = $sell;
        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->getBuy();
    }

    /**
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->getDateCreate();
    }

    /**
     * @return string
     */
    public function getDateFormatDMY()
    {
        return $this->getDateCreate()->format('d.m.Y');
    }

    /**
     * @param \DateTime $dateCreate
     * @return Course
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
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

    public function toArray()
    {
        return [
            'id'         => $this->getId(),
            'exchangeId' => $this->getExchangeId(),
            'nominal'    => $this->getNominal(),
            'buy'        => $this->getBuy(),
            'sell'       => $this->getSell(),
            'dateCreate' => $this->getDateFormatDMY(),
        ];
    }

}