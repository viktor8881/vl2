<?php

namespace Investments\Entity;

use Base\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="investments" )
 */
class Investments extends AbstractEntity
{

    const TYPE_BAY = 0;
    const TYPE_SELL = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /** @ORM\Column(name="type", type="integer") */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="\Exchange\Entity\Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     * @var Exchange
     */
    protected $exchange;

    /** @ORM\Column(name="amount", type="decimal", precision=6, scale=20) */
    protected $amount;

    /** @ORM\Column(name="course", type="decimal", precision=6, scale=20) */
    protected $course;

    /** @ORM\Column(name="`sum`", type="decimal", precision=6, scale=20) */
    protected $sum;

    /** @ORM\Column(name="date", type="date") */
    protected $date;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function setTypeBay()
    {
        $this->type = self::TYPE_BAY;
        return $this;
    }

    /**
     * @return $this
     */
    public function setTypeSell()
    {
        $this->type = self::TYPE_SELL;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBay()
    {
        return $this->type == self::TYPE_BAY;
    }

    /**
     * @return bool
     */
    public function isSell()
    {
        return $this->type == self::TYPE_SELL;
    }

    /**
     * @return Exchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getExchangeName()
    {
        $exchange = $this->getExchange();
        if ($exchange) {
            return $exchange->getName();
        }
        return '';
    }

    /**
     * @param Exchange $exchange
     * @return $this
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param float $course
     * @return $this
     */
    public function setCourse($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return float
     */
    public function getSum()
    {
        return (float)$this->sum;
    }

    /**
     * @param float $sum
     *
     * @return Investments
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

}