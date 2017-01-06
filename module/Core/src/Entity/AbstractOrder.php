<?php

namespace Core\Entity;

class AbstractOrder
{

    const ASC = 'ASC';
    const DESC = 'DESC';

    protected $typeOrder = null;


    public function __construct($typeOrder = null)
    {
        $this->typeOrder = self::ASC;
        $this->setTypeOrder($typeOrder);
    }

    /**
     * @param null $typeOrder
     * @return $this
     */
    public function setTypeOrder($typeOrder = null)
    {
        if ($typeOrder == self::DESC) {
            $this->typeOrder = self::DESC;
        } elseif ($typeOrder == self::ASC) {
            $this->typeOrder = self::ASC;
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTypeOrder()
    {
        return $this->typeOrder;
    }

}

