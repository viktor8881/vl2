<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 01.01.2017
 * Time: 12:45
 */

namespace Model;


interface IEmpty
{

    public function getId();

    public function toArray();

    public function setToArray(array $options= array());

}