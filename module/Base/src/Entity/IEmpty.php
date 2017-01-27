<?php

namespace Base\Entity;

/**
 * Interface IEmpty
 *
 * @package Base\Entity
 */
interface IEmpty
{

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $options
     */
    public function setOptions(array $options = array());

}