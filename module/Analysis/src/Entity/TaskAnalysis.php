<?php
namespace Analysis\Entity;

use Course\Entity\ICourse;
use Doctrine\ORM\Mapping as ORM;
use Base\Entity\AbstractEntity;
use Course\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Exchange\Entity\Exchange;

/**
 * @ORM\Entity
 * @ORM\Table(name="task_analysis")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({"1" = "TaskPercentAnalysis", "2" = "TaskOvertimeAnalysis"})
 */
abstract class TaskAnalysis extends AbstractEntity
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
    /**
     * @ORM\ManyToOne(targetEntity="\Exchange\Entity\Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     * @var Exchange
     */
    private $exchange;
    /** @ORM\Column(name="period", type="float") */
    protected $period;
    /**
     * @ORM\ManyToMany(targetEntity="\Course\Entity\Course")
     * @ORM\JoinTable(name="task_analysis_course",
     * joinColumns={@ORM\JoinColumn(name="analysis_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $courses;
    /** @ORM\Column(name="created", type="date") */
    protected $created;


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
        $this->courses = new ArrayCollection();
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
     *
     * @return TaskAnalysis
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
     *
     * @return TaskAnalysis
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param float $period
     *
     * @return TaskAnalysis
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return Course[]
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @param Course[] $courses
     *
     * @return TaskAnalysis
     */
    public function setCourses(array $courses)
    {
        usort($courses, array($this, 'sortCourseByDate'));
        $this->courses = $courses;
        return $this;
    }

    /**
     * @param ICourse $a
     * @param ICourse $b
     * @return int
     */
    private function sortCourseByDate(ICourse $a, ICourse $b)
    {
        $a = $a->getDate();
        $b = $b->getDate();
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return TaskAnalysis
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
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
     * @return array
     */
    public function getListMetal()
    {
        $result = [];
        /** @var $exchange Exchange */
        foreach ($this->getCourses() as $exchange) {
            if ($exchange->isMetal()) {
                $result[] = $exchange;
            }
        }
        return $result;
    }

    /**
     * @return mixed
     */
    abstract public function getType();

}