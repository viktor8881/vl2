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

    /**
     *
     */
    const TYPE_PERCENT = 1;
    /**
     *
     */
    const TYPE_OVER_TIME = 2;

    /**
     *
     */
    const MODE_ONLY_UP = 1;
    /**
     *
     */
    const MODE_ONLY_DOWN = 2;
    /**
     *
     */
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
    protected $period;

    /**
     * @ORM\ManyToMany(targetEntity="\Exchange\Entity\Exchange")
     * @ORM\JoinTable(name="task_exchange",
     * joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="exchange_id", referencedColumnName="id", unique=true)}
     * )
     * @todo - try rewrite on type=json_array
     */
    protected $exchanges;


    /**
     * @return array
     */
    static public function listTypeCustom()
    {
        return array(self::TYPE_PERCENT, self::TYPE_OVER_TIME);
    }

    /**
     * @return array
     */
    static public function listModes()
    {
        return array(self::TYPE_PERCENT, self::TYPE_OVER_TIME);
    }

    /**
     * Task constructor.
     *
     * @param array|null $options
     */
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
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
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
     * @return bool
     */
    public function isPercent()
    {
        return $this->getType() == self::TYPE_PERCENT;
    }

    /**
     * @return bool
     */
    public function isOvertime()
    {
        return $this->getType() == self::TYPE_OVER_TIME;
    }

    /**
     * @return bool
     */
    public function isModeOnlyUp()
    {
        return $this->getMode() == self::MODE_ONLY_UP;
    }

    /**
     * @return bool
     */
    public function isModeOnlyDown()
    {
        return $this->getMode() == self::MODE_ONLY_DOWN;
    }

    /**
     * @return bool
     */
    public function isModeUpDown()
    {
        return $this->getMode() == self::MODE_UP_DOWN;
    }

    /**
     * @return array
     */
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

    /**
     * @return int
     */
    public function countMetal()
    {
        return count($this->getListMetal());
    }

    /**
     * @return array
     */
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

    /**
     * @return int
     */
    public function countCurrency()
    {
        return count($this->getListCurrency());
    }

    /**
     * @return array
     */
    public function listMetalId()
    {
        $result = [];
        /** @var Exchange $exchange */
        foreach ($this->getExchanges() as $exchange) {
            if ($exchange->isMetal()) {
                $result[] = $exchange->getId();
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function listCurrencyId()
    {
        $result = [];
        /** @var Exchange $exchange */
        foreach ($this->getExchanges() as $exchange) {
            if ($exchange->isCurrency()) {
                $result[] = $exchange->getId();
            }
        }
        return $result;
    }

    /**
     * @return mixed
     */
    abstract public function getType();

}