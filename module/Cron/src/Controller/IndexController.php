<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendQueue\Queue;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        echo 'Hello world';
        return $this->getResponse();
    }
}
