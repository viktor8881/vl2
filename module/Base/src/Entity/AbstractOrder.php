<?php

namespace Base\Entity;

class AbstractOrder
{

    const ASC = 'ASC';
    const DESC = 'DESC';

    protected $typeOrder = null;


    public function __construct($typeOrder = null)
    {
        $this->setTypeOrder($typeOrder);
    }

    /**
     * @return null|string
     */
    public function getTypeOrder()
    {
        return $this->typeOrder;
    }

    /**
     * @param null $typeOrder
     *
     * @return $this
     */
    public function setTypeOrder($typeOrder = null)
    {
        if ($typeOrder == self::DESC) {
            $this->typeOrder = self::DESC;
        } else {
            $this->typeOrder = self::ASC;
        }
        return $this;
    }

}

