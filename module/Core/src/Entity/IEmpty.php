<?php

namespace Core\Entity;

interface IEmpty
{

    public function getId();

    public function toArray();

    public function setToArray(array $options = array());

}