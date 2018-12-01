<?php

namespace Exchange\Controller;

use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager as ManagerExchange;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    private $managerExchange;

    public function __construct(ManagerExchange $managerMetal)
    {
        $this->managerExchange = $managerMetal;
    }

    public function metalAction()
    {
        return new ViewModel(
            ['items' => $this->managerExchange->fetchAllMetal()]
        );
    }

    public function currencyAction()
    {
        return new ViewModel(
            ['items' => $this->managerExchange->fetchAllCurrency()]
        );
    }

    public function stockAction()
    {
        return new ViewModel(
            ['items' => $this->managerExchange->fetchAllStock()]
        );
    }

    public function addFavoriteAction()
    {
        $view = new JsonModel();
        $view->setTerminal(true);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $exchangeId = $this->params()->fromRoute('exchangeId');
            /** @var $exchange Exchange */
            $exchange = $this->managerExchange->get($exchangeId);
            if ($exchange) {
                $exchange->favorite();
                $this->managerExchange->update($exchange);
            } else {
                $this->getResponse()->setStatusCode(403);
            }
        } else {
            $this->getResponse()->setStatusCode(403);
        }
        return $view;
    }

    public function deleteFavoriteAction()
    {
        $view = new JsonModel();
        $view->setTerminal(true);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $exchangeId = $this->params()->fromRoute('exchangeId');
            /** @var $exchange Exchange */
            $exchange = $this->managerExchange->get($exchangeId);
            if ($exchange) {
                $exchange->unFavorite();
                $this->managerExchange->update($exchange);
            } else {
                $this->getResponse()->setStatusCode(403);
            }
        } else {
            $this->getResponse()->setStatusCode(403);
        }
        return $view;
    }

    public function hideAnalysisAction()
    {
        $view = new JsonModel();
        $view->setTerminal(true);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $exchangeId = $this->params()->fromRoute('exchangeId');
            /** @var $exchange Exchange */
            $exchange = $this->managerExchange->get($exchangeId);
            if ($exchange) {
                $exchange->hide();
                $this->managerExchange->update($exchange);
            } else {
                $this->getResponse()->setStatusCode(403);
            }
        } else {
            $this->getResponse()->setStatusCode(403);
        }
        return $view;
    }

}
