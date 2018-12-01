<?php

namespace Exchange\Entity;

use Base\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="exchange" )
 */
class Exchange extends AbstractEntity
{

    const TYPE_METAl = 1;
    const TYPE_CURRENCY = 2;
    const TYPE_STOCK = 3;

    const CODE_CURRENCY_MAIN = 'RUB-643';
    const SHORT_NAME_USD = 'USD';
    const SHORT_NAME_EUR = 'EUR';
    const SHORT_NAME_GOLD = 'Золото';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /** @ORM\Column(name="type", type="smallint") */
    protected $type;
    /** @ORM\Column(name="code", type="string", length=20) */
    protected $code;
    /** @ORM\Column(name="name", type="string", length=255) */
    protected $name;
    /** @ORM\Column(name="short_name", type="string", length=255) */
    protected $shortName;
    /** @ORM\Column(name="moex_secid", type="string", length=10, nullable=true) */
    protected $moexSecId;
    /** @ORM\Column(name="favorite", type="boolean") */
    protected $favorite;
    /** @ORM\Column(name="hide", type="boolean") */
    protected $hide;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMetal()
    {
        return $this->getType() == self::TYPE_METAl;
    }

    /**
     * @return bool
     */
    public function isStock()
    {
        return $this->getType() == self::TYPE_STOCK;
    }

    /**
     * @return bool
     */
    public function isCurrency()
    {
        return $this->getType() == self::TYPE_CURRENCY;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return bool
     */
    public function isUSD()
    {
        return $this->getShortName() === self::SHORT_NAME_USD;
    }

    /**
     * @return bool
     */
    public function isEUR()
    {
        return $this->getShortName() === self::SHORT_NAME_EUR;
    }

    /**
     * @return bool
     */
    public function isGold()
    {
        return $this->getShortName() === self::SHORT_NAME_GOLD;
    }

    /**
     * @return mixed
     */
    public function getMoexSecId()
    {
        return $this->moexSecId;
    }

    /**
     * @param mixed $moexSecId
     * @return Exchange
     */
    public function setMoexSecId($moexSecId)
    {
        $this->moexSecId = $moexSecId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param mixed $favorite
     * @return Exchange
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHide()
    {
        return $this->hide;
    }

    /**
     * @param mixed $hide
     * @return Exchange
     */
    public function setHide($hide)
    {
        $this->hide = $hide;
        return $this;
    }

    /**
     * @return Exchange
     */
    public function hide()
    {
        return $this->setHide(true);
    }

    /**
     * @return Exchange
     */
    public function show()
    {
        return $this->setHide(false);
    }

    /**
     * @return Exchange
     */
    public function favorite()
    {
        return $this->setFavorite(true);
    }

    /**
     * @return Exchange
     */
    public function unFavorite()
    {
        return $this->setFavorite(false);
    }

}