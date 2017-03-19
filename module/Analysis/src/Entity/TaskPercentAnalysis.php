<?php
namespace Analysis\Entity;


use Course\Entity\Course;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaskPercentAnalysis extends TaskAnalysis
{

    /** @ORM\Column(name="percent", type="integer") */
    protected $percent;

    public function getType()
    {
        return self::TYPE_PERCENT;
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param float $percent
     *
     * @return $this
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * @return Course|null
     */
    public function getFirstCourse()
    {
        return $this->getCourses()->first();
    }

    /**
     * @return Course|null
     */
    public function getLastCourse()
    {
        return $this->getCourses()->last();
    }

    /**
     * @return float
     */
    public function getStartValue()
    {
        $course = $this->getFirstCourse();
        if ($course) {
            return $course->getValue();
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getStartDateFormatDMY()
    {
        $course = $this->getFirstCourse();
        if ($course) {
            return $course->getDateFormatDMY();
        }
        return '';
    }

    /**
     * @return float
     */
    public function getEndValue()
    {
        $course = $this->getLastCourse();
        if ($course) {
            return $course->getValue();
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getEndDateFormatDMY()
    {
        $course = $this->getLastCourse();
        if ($course) {
            return $course->getDateFormatDMY();
        }
        return '';
    }

    /**
     * @return bool
     */
    public function isQuotesGrowth()
    {
        return $this->getStartValue() < $this->getEndValue();
    }

    /**
     * @return bool
     */
    public function isQuotesFall()
    {
        return $this->getStartValue() > $this->getEndValue();
    }

    /**
     * @return float
     */
    public function getDiffMoneyValue()
    {
        return abs($this->getStartValue() - $this->getEndValue());
    }

    /**
     * @return float
     */
    public function getDiffPercent()
    {
        return 100 - (abs($this->getStartValue() * 100 / $this->getEndValue()));
    }

}
