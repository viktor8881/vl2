<?php
namespace Analysis\Service;


use Analysis\Entity\FigureAnalysisCollection;
use Analysis\Entity\TaskOvertimeAnalysisCollection;
use Analysis\Entity\TaskPercentAnalysisCollection;
use Exchange\Entity\Exchange;
use Zend\Mail\Message;

class MailService
{
    /** @var string */
    private $fromAddress;
    /** @var string */
    private $toAddress;

    public function __construct($fromAddress, $toAddress)
    {
        $this->fromAddress = $fromAddress;
        $this->toAddress = $toAddress;
    }


    public function sendAnalysis(Exchange $exchange,
        TaskOvertimeAnalysisCollection $taskOvertimeAnalysisCollection,
        TaskPercentAnalysisCollection $taskPercentAnalysisCollection,
        FigureAnalysisCollection $figureAnalysisCollection)
    {
        $mail = new Message();
        $mail->setEncoding('UTF-8');
        $mail->setFrom($this->fromAddress);
        $mail->addTo($this->toAddress);
        $mail->setSubject('Валюта '.$exchange->getName());

        $layout = self::getLayout();
        $view = self::getView();
        $layoutСontent = '';
        $layout->investmentName = $exchange->getName();
        if ($taskOvertimeAnalysisCollection) {
            $view->assign('overtime', $taskOvertimeAnalysisCollection);
            $layoutСontent .= $view->render('overtime.phtml');
        }
        if ($taskPercentAnalysisCollection->count()) {
            $view->clearVars();
            $view->assign('percents', $taskPercentAnalysisCollection);
            $layoutСontent .= $view->render('percents.phtml');
        }
        if ($figureAnalysisCollection->count()) {
            $view->clearVars();
            $view->assign('figures', $figureAnalysisCollection);
            $layoutСontent .= $view->render('figures.phtml');
        }

        if ($layoutСontent) {
            $layout->content = $layoutСontent;
            $layout->footer = $view->render('footer.phtml');
            $mail->setBodyHtml($layout->render());
            $mail->send();
        }
    }

}
