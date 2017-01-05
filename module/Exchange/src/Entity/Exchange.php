<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:32
 */

namespace Exchange\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="exchange" )
 */
class Exchange
{

    const TYPE_METAl = 1;
    const TYPE_CURRENCY = 2;

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

}