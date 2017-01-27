<?php
namespace Base\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ErrorMessage extends AbstractPlugin
{

    public function add($mess)
    {
        $helperFlash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $helperFlash->setNamespace('error');
        $helperFlash->addMessage($mess);
    }
}