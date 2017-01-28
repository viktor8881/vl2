<?php
namespace Base\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class ErrorMessage
 *
 * @package Base\Controller\Plugin
 */
class ErrorMessage extends AbstractPlugin
{

    /**
     * @param $mess
     */
    public function add($mess)
    {
        $helperFlash = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'FlashMessenger'
        );
        $helperFlash->setNamespace('error');
        $helperFlash->addMessage($mess);
    }
}