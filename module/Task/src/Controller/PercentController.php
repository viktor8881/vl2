<?php

namespace Task\Controller;

use Exchange\Service\ExchangeManager;
use Task\Form\PercentForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PercentController extends AbstractActionController
{

    private $exchangeManager;


    public function __construct(ExchangeManager $exchangeManager)
    {
        $this->exchangeManager = $exchangeManager;
    }

    public function addAction()
    {
        $form = new PercentForm($this->exchangeManager);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {

                    $data = $form->getData();
                    $user = $this->userManager->addUser($data);

                    return $this->redirect()->toRoute('users',
                        ['action' => 'view', 'id' => $user->getId()]);


            }
        }
        return new ViewModel([
            'form' => $form
        ]);
    }
}
