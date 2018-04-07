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

    const CODE_CURRENCY_MAIN = 'RUB-643';
    const SHORT_NAME_USD = 'USD';
    const SHORT_NAME_EUR = 'EUR';


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

}