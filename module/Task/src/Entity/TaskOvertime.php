<?php

namespace Task\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TaskOvertime extends Task
{

    public function getType()
    {
        return self::TYPE_OVER_TIME;
    }

}