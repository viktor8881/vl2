<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:32
 */

namespace Task\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({"1" = "TaskPercent", "2" = "TaskOvertime"})
 */
abstract class Task extends AbstractEntity
{

    const TYPE_PERCENT = 1;
    const TYPE_OVER_TIME = 2;

    const MODE_ONLY_UP = 1;
    const MODE_ONLY_DOWN = 2;
    const MODE_UP_DOWN = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /** @ORM\Column(name="mode", type="integer") */
    protected $mode;

    /** @ORM\Column(name="period", type="float") */
    protected $percent;

    /**
     * @ORM\ManyToMany(targetEntity="\Exchange\Entity\Exchange")
     * @ORM\JoinTable(name="task_exchange",
     * joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="exchange_id", referencedColumnName="id", unique=true)}
     * )
     * @todo - try rewrite on type=json_array
     */
    protected $exchanges;

    /** @ORM\Column(name="body", type="json_array") */
    protected $body;


    static public function listTypeCustom()
    {
        return array(self::TYPE_PERCENT, self::TYPE_OVER_TIME);
    }

    static public function listModes()
    {
        return array(self::TYPE_PERCENT, self::TYPE_OVER_TIME);
    }

    public function __construct(array $options = null)
    {
        $this->exchanges = new ArrayCollection();
        parent::__construct($options);
    }

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
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

    /**
     * @return mixed
     */
    public function getExchanges()
    {
        return $this->exchanges;
    }

    /**
     * @param mixed $exchanges
     */
    public function setExchanges($exchanges)
    {
        $this->exchanges = $exchanges;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    public function isPercent()
    {
        return $this->getType() == self::TYPE_PERCENT;
    }

    public function isOvertime()
    {
        return $this->getType() == self::TYPE_OVER_TIME;
    }

    public function isModeOnlyUp()
    {
        return $this->getMode() == self::MODE_ONLY_UP;
    }

    public function isModeOnlyDown()
    {
        return $this->getMode() == self::MODE_ONLY_DOWN;
    }

    public function isModeUpDown()
    {
        return $this->getMode() == self::MODE_UP_DOWN;
    }

    public function getListMetal()
    {
        $result = [];
        /** @var $exchange Exchange */
        foreach ($this->getExchanges() as $exchange) {
            if ($exchange->isMetal()) {
                $result[] = $exchange;
            }
        }
        return $result;
    }

    public function countMetal()
    {
        return count($this->getListMetal());
    }

    public function getListCurrency()
    {
        $result = [];
        /** @var $exchange Exchange */
        foreach ($this->getExchanges() as $exchange) {
            if ($exchange->isCurrency()) {
                $result[] = $exchange;
            }
        }
        return $result;
    }

    public function countCurrency()
    {
        return count($this->getListCurrency());
    }

    abstract public function getType();

}