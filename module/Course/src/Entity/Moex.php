<?php


namespace Course\Entity;

use Base\Entity\AbstractEntity;
use Base\Entity\IEmpty;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="moex_course")
 */
class Moex extends AbstractEntity implements IEmpty
{

    const SQL_LAST_RATE_BY_EXCHANGE_ID =  'SELECT * FROM moex_course WHERE exchange_id=%d ORDER BY trade_date_time DESC LIMIT 1';

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

    /**
     * @ORM\Column(name="secid", type="string")
     * @var string
     */
    protected $secId;

    /**
     * @ORM\Column(name="rate", type="decimal", precision=6, scale=20)
     * @var float
     */
    protected $rate;

    /**
     * @ORM\Column(name="trade_date_time", type="datetime")
     * @var \DateTime
     */
    protected $tradeDateTime;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Moex
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
     * @return int
     */
    public function getExchangeId()
    {
        return $this->getExchange()->getId();
    }

    /**
     * @return string
     */
    public function getExchangeName()
    {
        return $this->getExchange()->getName();
    }

    /**
     * @param Exchange $exchange
     * @return Moex
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecId()
    {
        return $this->secId;
    }

    /**
     * @param string $secId
     *
     * @return Moex
     */
    public function setSecId($secId)
    {
        $this->secId = $secId;
        return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->getRate();
    }

    /**
     * @param float $rate
     * @return Moex
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTradeDateTime()
    {
        return $this->tradeDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->getTradeDateTime();
    }

    /**
     * @return string
     */
    public function getDateFormatDMY()
    {
        return $this->getTradeDateTime()->format('d.m.Y');
    }

    /**
     * @param string $format
     * @return string
     */
    public function getTradeDateTimeByFormat($format)
    {
        return $this->tradeDateTime->format($format);
    }

    /**
     * @return string
     */
    public function getTradeDateTimeForChart()
    {
        return $this->getTradeDateTimeByFormat('U') . '000';
    }

    /**
     * @param \DateTime $tradeDateTime
     * @return Moex
     */
    public function setTradeDateTime(\DateTime $tradeDateTime)
    {
        $this->tradeDateTime = $tradeDateTime;
        return $this;
    }

}