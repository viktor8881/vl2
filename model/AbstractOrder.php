<?php
/**
 * User: Viktor
 * Date: 01.01.2017
 * Time: 12:50
 */

namespace Model;


abstract class AbstractOrder
{

    const DESC = 'desc';
    const ASC = 'asc';

    private $order;

    public function __construct($order = self::ASC)
    {
        if (!in_array($order, [self::ASC, self::DESC])) {
            $order = self::ASC;
        }
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

}
