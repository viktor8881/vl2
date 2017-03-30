<?php

namespace Task\Controller;

use Exchange\Service\ExchangeManager;
use Task\Entity\TaskPercent;
use Task\Form\PercentForm;
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
                $data = $form->getDataForEntity();
                $data['exchanges'] = $this->exchangeManager->fetchAllByListId(
                    $data['listIdExchanges']
                );
                /** @var TaskPercent $task */
                $task = $this->taskManager->createEntity($data);
                $this->taskManager->insert($task);

                $this->flashMessenger()->addSuccessMessage(
                    'Задача добавлена.'
                );
                return $this->redirect()->toRoute('tasks');
            }
        }
        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        /** @var TaskPercent $task */
        $task = $this->taskManager->get(
            (int)$this->params()->fromRoute('id', -1)
        );
        if (!$task or !$task->isPercent()) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }

        $form = new PercentForm(
            $this->exchangeManager->fetchAllMetal(),
            $this->exchangeManager->fetchAllCurrency()
        );
        $form->setLabelSubmit('Редактировать');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getDataForEntity();
                $data['exchanges'] = $this->exchangeManager->fetchAllByListId($data['listIdExchanges']);
                $task->setFromArray($data);
                $this->taskManager->update($task);

                $this->flashMessenger()->addSuccessMessage('Задача изменена.');
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
        return new ViewModel(['form' => $form]);
    }

    public function deleteAction()
    {
        /** @var TaskPercent $task */
        $task = $this->taskManager->get(
            (int)$this->params()->fromRoute('id', -1)
        );
        if (!$task or !$task->isPercent()) {
            $this->getResponse()->setStatusCode(404);
            return $this->getResponse();
        }
        $this->taskManager->delete($task);
        $this->flashMessenger()->addSuccessMessage('Задача удалена.');
        return $this->redirect()->toRoute('tasks');
    }
}
