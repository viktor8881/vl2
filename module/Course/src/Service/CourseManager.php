<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Course\Service;


use Doctrine\ORM\EntityRepository;
use Course\Entity\Course;
use Zend\Stdlib\ArrayObject;

class CourseManager
{

    private $repositoryEntity;

    public function __construct(EntityRepository $repositoryEntity)
    {
        $this->repositoryEntity = $repositoryEntity;
    }




//    public function fetchAllMetal()
//    {
//        return $this->repositoryEntity->findBy(['type' => Course::TYPE_METAl]);
//    }
//
//    public function fetchAllCurrency()
//    {
//        return $this->repositoryEntity->findBy(['type' => Course::TYPE_CURRENCY]);
//    }

}