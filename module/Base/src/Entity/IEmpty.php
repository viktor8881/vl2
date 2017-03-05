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