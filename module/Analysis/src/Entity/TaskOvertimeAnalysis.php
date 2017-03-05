<?php

namespace Analysis\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaskOvertimeAnalysis extends TaskAnalysis
{

    public function getType()
    {
        return self::TYPE_OVER_TIME;
    }

}