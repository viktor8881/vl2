<?php


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


//        var_dump($this->dataNow->format('d.m.Y H:i:s'));
//        pr($this->getPluginManager());
        return new ViewModel();
    }

    public function aboutAction()
    {
        return new ViewModel();
    }
}
