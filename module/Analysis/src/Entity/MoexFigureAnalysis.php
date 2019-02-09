<?php
namespace Analysis\Entity;

use Base\Entity\AbstractEntity;
use Course\Entity\CacheCourse;
use Course\Entity\MoexCacheCourse;
use Doctrine\Common\Collections\ArrayCollection;
use Exchange\Entity\Exchange;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="moex_figure_analysis")
 */
class MoexFigureAnalysis extends AbstractEntity implements FigureAnalysisInterface
{

    const SEPARATE = ';';

    const FIGURE_DOUBLE_TOP = 1;
    const FIGURE_DOUBLE_BOTTOM = 2;
    const FIGURE_TRIPLE_TOP = 3;
    const FIGURE_TRIPLE_BOTTOM = 4;
    const FIGURE_HEADS_HOULDERS = 5;
    const FIGURE_RESERVE_HEADS_HOULDERS = 6;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="\Exchange\Entity\Exchange")
     * @ORM\JoinColumn(name="exchange_id", referencedColumnName="id")
     * @var Exchange
     */
    private $exchange;
    /** @ORM\Column(name="figure", type="integer") */
    private $figure;
    /**
     * @ORM\ManyToMany(targetEntity="\Course\Entity\MoexCacheCourse", fetch="EAGER")
     * @ORM\JoinTable(name="moex_figure_analysis_cache_courses",
     * joinColumns={@ORM\JoinColumn(name="analysis_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="cache_course_id", referencedColumnName="id")}
     * )
     */
    protected $cacheCourses;
    /** @ORM\Column(name="created", type="date") */
    private $created;


    /**
     * Task constructor.
     *
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        $this->cacheCourses = new ArrayCollection();
        parent::__construct($options);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
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
     * @return $this
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    /**
     * @return int
     */
    public function getFigure()
    {
        return $this->figure;
    }

    /**
     * @param int $figure
     *
     * @return $this
     */
    public function setFigure($figure)
    {
        $this->figure = $figure;
        return $this;
    }

    /**
     * @return MoexCacheCourse[]
     */
    public function getCacheCourses()
    {
        return $this->cacheCourses;
    }

    /**
     * @param MoexCacheCourse[] $cacheCourses
     *
     * @return $this
     */
    public function setCacheCourses($cacheCourses)
    {
        $this->cacheCourses = $cacheCourses;
        return $this;
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
     * @return $this
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstDate()
    {
        /** @var CacheCourse $cacheCourse */
        $cacheCourse = $this->getCacheCourses()->first();
        if ($cacheCourse) {
            return $cacheCourse->getFirstDate();
        }
        return null;
    }

    /**
     * @return CacheCourse
     */
    public function getFirstCacheCourse()
    {
        return $this->getCacheCourses()->first();
    }

    /**
     * @return \DateTime|null
     */
    public function getLastDate()
    {
        /** @var CacheCourse $cacheCourse */
        $cacheCourse = $this->getCacheCourses()->last();
        if ($cacheCourse) {
            return $cacheCourse->getLastDate();
        }
        return null;
    }

    /**
     * @return float
     */
    public function getPercentCacheCourses()
    {
        /** @var CacheCourse $cacheCourse */
        $cacheCourse = $this->getCacheCourses()->first();
        if ($cacheCourse) {
            return $cacheCourse->getPercent();
        }
        return 0;
    }

    /**
     * @return int
     */
    public function countDataValues(): int
    {
        $result = 0;
        /** @var $cacheCourse MoexCacheCourse */
        foreach ($this->getCacheCourses() as $cacheCourse) {
            $result += $cacheCourse->countDataValue();
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function countFirstDataValue()
    {
        return $this->getFirstCacheCourse()->countDataValue();
    }

    /**
     * @return bool
     */
    public function isTreeBottom(): bool
    {
        return $this->getFigure() === self::FIGURE_TRIPLE_BOTTOM;
    }

    /**
     * @return bool
     */
    public function isTreeTop(): bool
    {
        return $this->getFigure() === self::FIGURE_TRIPLE_TOP;
    }

    /**
     * @return bool
     */
    public function isDoubleTop(): bool
    {
        return $this->getFigure() === self::FIGURE_DOUBLE_TOP;
    }

    /**
     * @return bool
     */
    public function isDoubleBottom(): bool
    {
        return $this->getFigure() === self::FIGURE_DOUBLE_BOTTOM;
    }

    /**
     * @return bool
     */
    public function isHeadShoulders(): bool
    {
        return $this->getFigure() === self::FIGURE_HEADS_HOULDERS;
    }

    /**
     * @return bool
     */
    public function isRevertHeadShoulders(): bool
    {
        return $this->getFigure() === self::FIGURE_RESERVE_HEADS_HOULDERS;
    }

}
