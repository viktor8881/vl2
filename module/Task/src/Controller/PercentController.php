<?php

namespace Task\Controller;

use Exchange\Service\ExchangeManager;
use Task\Form\PercentForm;
use Task\Entity\TaskPercent;
use Task\Service\TaskPercentManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PercentController extends AbstractActionController
{

    private $taskManager;
    private $exchangeManager;


    public function __construct(TaskPercentManager $taskManager,
        ExchangeManager $exchangeManager
    ) {
        $this->taskManager = $taskManager;
        $this->exchangeManager = $exchangeManager;
    }

    public function addAction()
    {
        $form = new PercentForm(
            $this->exchangeManager->fetchAllMetal(),
            $this->exchangeManager->fetchAllCurrency()
        );
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getDataForItem();
                /** @var TaskPercent $task */
                $task = $this->taskManager->createEntity($data);
                $task->setExchanges(
                    $this->exchangeManager->fetchAllByListId($data['exchanges'])
                );
                $this->taskManager->insert($task);

                return $this->redirect()->toRoute('tasks');
            }
        }
        return new ViewModel(
            [
                'form' => $form
            ]
        );
    }

    public function editAction()
    {
        /** @var TaskPercent $task */
        $task = $this->taskManager->get((int)$this->params()->fromRoute('id', -1));
        if (!$task or !$task->isPercent()) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new PercentForm(
            $this->exchangeManager->fetchAllMetal(),
            $this->exchangeManager->fetchAllCurrency()
        );

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getDataForItem();
                $task->setOptions($data);
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
                    'percent'    => $task->getPercent(),
                    'period'     => $task->getPeriod(),
                    'metals'     => $task->listMetalId(),
                    'currencies' => $task->listCurrencyId()
                )
            );
        }

        return new ViewModel(
            array(
                'task' => $task,
                'form' => $form
            )
        );
    }

    public function deleteAction()
    {
        /** @var TaskPercent $task */
        $task = $this->taskManager->get((int)$this->params()->fromRoute('id', -1));
        if (!$task or !$task->isPercent()) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $this->taskManager->delete($task);
        return $this->redirect()->toRoute('tasks');
    }
}
