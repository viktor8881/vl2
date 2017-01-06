<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:32
 */

namespace Course\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 */
class Course extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     * @ORM\Column(name="exchange_id")
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
     * @param Exchange $exchange
     */
    public function setExchange(Exchange $exchange)
    {
        $this->exchange = $exchange;
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
     * @return mixed
     */
    public function getBuy()
    {
        return $this->buy;
    }

    /**
     * @param mixed $buy
     */
    public function setBuy($buy)
    {
        $this->buy = $buy;
    }

    /**
     * @return mixed
     */
    public function getSell()
    {
        return $this->sell;
    }

    /**
     * @param mixed $sell
     */
    public function setSell($sell)
    {
        $this->sell = $sell;
    }

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

    public function getDate()
    {
        return $this->getDateCreate();
    }

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

    public function isMetal()
    {
        return $this->getExchange()->isMetal();
    }

    public function isCurrency()
    {
        return $this->getExchange()->isCurrency();
    }

}