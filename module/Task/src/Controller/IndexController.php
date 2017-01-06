<?php

namespace Task\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{


    public function indexAction()
    {
        return ['items' => $this->taskmanager
            ->fetchAllCustomOrderByOwerTime()];
    }
}
