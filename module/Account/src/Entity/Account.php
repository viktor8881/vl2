<?php

namespace Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use Base\Entity\AbstractEntity;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="account" )
 */
class Account extends AbstractEntity
{
    const MAIN = 1;

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
    /** @ORM\Column(name="balance", type="decimal", precision=6, scale=20) */
    protected $balance;

    /** @ORM\Column(name="main", type="smallint") */
    protected $main;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
    }

    public function isMetal()
    {
        return $this->getExchange()->isMetal();
    }

    public function isCurrency()
    {
        return $this->getExchange()->isCurrency();
    }

    public function getExchangeName()
    {
        return $this->getExchange()->getName();
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @param $balance
     * @return $this
     */
    public function replenishBalance($balance)
    {
        $this->balance += $balance;
        return $this;
    }

    /**
     * @param $balance
     * @return $this
     */
    public function subtractionBalance($balance)
    {
        $this->balance -= $balance;
        return $this;
    }

    /**
     * @param int $main
     */
    public function setMain($main)
    {
        $this->main = $main;
    }

    /**
     * @return bool
     */
    public function isMain()
    {
        return $this->main == self::MAIN;
    }
}