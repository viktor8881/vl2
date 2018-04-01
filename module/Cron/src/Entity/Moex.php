<?php


namespace Cron\Entity;

use Base\Entity\AbstractEntity;
use Base\Entity\IEmpty;
use Doctrine\ORM\Mapping as ORM;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="moex_course")
 */
class Moex extends AbstractEntity implements IEmpty
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

    /**
     * @ORM\Column(name="secid", type="string")
     * @var string
     */
    protected $secid;

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
    public function getSecid()
    {
        return $this->secid;
    }

    /**
     * @param string $secid
     * @return Moex
     */
    public function setSecid($secid)
    {
        $this->secid = $secid;
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
     * @param \DateTime $tradeDateTime
     * @return Moex
     */
    public function setTradeDateTime(\DateTime $tradeDateTime)
    {
        $this->tradeDateTime = $tradeDateTime;
        return $this;
    }

}