<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{

    private $dataNow;


    public function __construct(\DateTime $dataNow)
    {
        $this->dataNow = $dataNow;
    }

    public function indexAction()
    {

//        var_dump($this->access()->checkAccess('index'));
//        var_dump($this->dataNow->format('d.m.Y H:i:s'));
//        var_dump($this->getPluginManager());
        return new ViewModel();
    }

    public function aboutAction()
    {
        return new ViewModel();
    }
}
