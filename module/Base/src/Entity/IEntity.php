<?php

namespace Base\Entity;

/**
 * Interface IEntity
 *
 * @package Base\Entity
 */
interface IEntity
{

    /**
     * @return string
     */
    public function getId();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $options
     */
    public function setFromArray(array $options = []);

}