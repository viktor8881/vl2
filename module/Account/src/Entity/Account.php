<?php

namespace Account\Entity;

use Base\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="account" )
 */
class Account extends AbstractEntity
{
    const MAIN = 1;
    const NO_MAIN = 0;

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
    protected $balance = 0;

    /** @ORM\Column(name="main", type="integer") */
    protected $main = self::NO_MAIN;

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
     * @return $this
     */
    public function setExchange(Exchange $exchange)
    {
        $this->exchange = $exchange;
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
     * @return string
     */
    public function getShortNameExchange()
    {
        return $this->getExchange()->getShortName();
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
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @param $balance
     * @return $this
     */
    public function addBalance($balance)
    {
        $this->balance += $balance;
        return $this;
    }

    /**
     * @param $balance
     * @return $this
     */
    public function subBalance($balance)
    {
        $this->balance -= $balance;
        return $this;
    }

    /**
     * @param int $main
     * @return $this
     */
    public function setMain($main)
    {
        $this->main = $main;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMain()
    {
        return $this->main == self::MAIN;
    }


}