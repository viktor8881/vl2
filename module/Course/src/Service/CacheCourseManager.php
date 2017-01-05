<?php
/**
 * Created by PhpStorm.
 * User: Viktor
 * Date: 18.12.2016
 * Time: 16:42
 */

namespace Course\Service;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Course\Entity\Course;
use Zend\Stdlib\ArrayObject;

class CacheCourseManager
{

    private $repositoryEntity;

    public function __construct(EntityRepository $repositoryEntity)
    {
        $this->repositoryEntity = $repositoryEntity;
    }

    public function fetchAllByCriteria(ArrayObject $criteria)
    {

//        foreach($criteria as $criterion) {
//            $mainCriteria->andWhere($criterion->getCriterion());
//        }
//        pr($mainCriteria);
        exit;
    }

}