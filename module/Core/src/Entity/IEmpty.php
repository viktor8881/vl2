<?php

namespace Core\Entity;

/**
 * Interface IEmpty
 *
 * @package Core\Entity
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