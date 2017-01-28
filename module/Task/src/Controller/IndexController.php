<?php

namespace Task\Controller;

use Task\Service\TaskOvertimeManager;
use Task\Service\TaskPercentManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
        $view = new ViewModel(['tasks' => array_merge(
            $this->taskOvertimeManager->fetchAllOrderById(),
            $this->taskPercentManager->fetchAllOrderById()
        )]);
        return $view;
    }
}
