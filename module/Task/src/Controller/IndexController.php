<?php

namespace Task\Controller;

use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    private $taskPercentManager;
    private $taskOvertimeManager;


    public function __construct(TaskPercentManager $taskPercentManager,
        TaskOvertimeManager $taskOvertimeManager
    ) {
        $this->taskPercentManager = $taskPercentManager;
        $this->taskOvertimeManager = $taskOvertimeManager;
    }

    public function indexAction()
    {
        return ['tasks' => array_merge(
            $this->taskOvertimeManager->fetchAllOrderById(),
            $this->taskPercentManager->fetchAllOrderById()
        )];
    }
}
