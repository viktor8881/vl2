<?php

namespace Task\Controller;

use Exchange\Service\ExchangeManager;
use Task\Entity\TaskOvertime;
use Task\Form\OvertimeForm;
use Task\Service\TaskOvertimeManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OvertimeController extends AbstractActionController
{

    private $taskManager;
    private $exchangeManager;


    public function __construct(TaskOvertimeManager $taskManager,
        ExchangeManager $exchangeManager
    ) {
        $this->taskManager = $taskManager;
        $this->exchangeManager = $exchangeManager;
    }

    public function addAction()
    {
        $form = new OvertimeForm(
            $this->exchangeManager->fetchAllMetal(),
            $this->exchangeManager->fetchAllCurrency()
        );
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getDataForEntity();
                /** @var TaskOvertime $task */
                $task = $this->taskManager->createEntity($data);
                $task->setExchanges($this->exchangeManager->fetchAllByListId($data['exchanges']));
                $this->taskManager->insert($task);

                return $this->redirect()->toRoute('tasks');
            }
        }
        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var TaskOvertime $task */
        $task = $this->taskManager->get((int)$this->params()->fromRoute('id', -1));
        if (!$task  or !$task->isOvertime()) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $form = new OvertimeForm(
            $this->exchangeManager->fetchAllMetal(),
            $this->exchangeManager->fetchAllCurrency()
        );
        $form->setLabelSubmit('Редактировать');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getDataForEntity();
                $task->setFromArray($data);
                $task->setExchanges(
                    $this->exchangeManager->fetchAllByListId($data['exchanges'])
                );
                $this->taskManager->update($task);

                return $this->redirect()->toRoute('tasks');
            }
        } else {
            $form->setData(
                array(
                    'mode'       => $task->getMode(),
                    'period'     => $task->getPeriod(),
                    'metals'     => $task->listMetalId(),
                    'currencies' => $task->listCurrencyId()
                )
            );
        }

        return new ViewModel(['form' => $form]);
    }

    public function deleteAction()
    {
        /** @var TaskOvertime $task */
        $task = $this->taskManager->get((int)$this->params()->fromRoute('id', -1));
        if (!$task  or !$task->isOvertime()) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }
        $this->taskManager->delete($task);
        return $this->redirect()->toRoute('tasks');
    }
}
