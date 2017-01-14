<?php

namespace Task\Controller;

use Task\Service\TaskManager;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    private $taskManager;


    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    public function indexAction()
    {
        return ['tasks' => $this->taskManager
            ->fetchAllOrderById()];
    }
}
