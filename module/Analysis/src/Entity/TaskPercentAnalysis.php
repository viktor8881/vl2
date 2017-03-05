<?php
namespace Analysis\Entity;


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

}
