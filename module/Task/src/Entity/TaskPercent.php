<?php

namespace Task\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaskPercent extends Task
{

    /** @ORM\Column(name="percent", type="integer") */
    protected $percent;

    public function getType()
    {
        return self::TYPE_PERCENT;
    }


    /**
     * @return mixed
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $period
     */
    public function setPercent($period)
    {
        $this->percent = $period;
    }

}