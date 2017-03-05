<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendQueue\Queue;

class IndexController extends AbstractActionController
{

    const TASK_RECEIVE_DATA = 'receive_data';
    const TASK_CACHE_DATA = 'cache_data';
    const TASK_ANALYSIS = 'analysis';
    const TASK_SEND_MESSAGE = 'send_message';

    /** @var Queue */
    private $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }


//INSERT INTO `message` (`message_id`, `queue_id`, `handle`, `body`, `md5`, `timeout`, `created`) VALUES
//(626, 1, NULL, 'analysis', '3b671c883959a8ef434b85a104c293d4', NULL, 1487940393);


    public function indexAction()
    {
//        $dateNow = new Date();
        $queue = $this->queue;

//        $queue->send(self::TASK_RECEIVE_DATA); exit;

        $messages = $queue->receive();
        foreach ($messages as $message) {
            $body = $message->body;
            switch ($body) {
                case self::TASK_RECEIVE_DATA:
                    $this->forward()->dispatch(CourseController::class, array('action'=>'receive'));
                    $queue->send(self::TASK_CACHE_DATA);
                    break;
                case self::TASK_CACHE_DATA:
                    $this->forward()->dispatch(CacheCourseController::class, array('action'=>'fill-cache'));
                    $queue->send(self::TASK_ANALYSIS);
                    break;
                case self::TASK_ANALYSIS:
                    $this->forward()->dispatch(TaskController::class, array('action'=>'task'));
                    $queue->send(self::TASK_SEND_MESSAGE);
                    break;
                case self::TASK_SEND_MESSAGE:
//                    $this->sendMessage($dateNow);
                    $queue->send(self::TASK_RECEIVE_DATA);
                    break;
                default:
                    throw new \Exception('unknown type task.');
                    break;
            }
            $queue->deleteMessage($message);
        }
        return $this->getResponse();
    }



//    private function sendMessage(Core_Date $dateNow) {
//        // readAll analysis currency by date
//        $analysis = $this->getManager('analysisCurrency')->fetchAllByDate($dateNow);
//        if ($analysis->count()) {
//            foreach ($analysis->getCurrencies() as $currency) {
//                Core_Mail::sendAnalysisCurrency($currency,
//                    $analysis->getOvertimeByCurrencyCode($currency->getCode()),
//                    $analysis->listPercentByCurrencyCode($currency->getCode()),
//                    $analysis->listFigureByCurrencyCode($currency->getCode()));
//            }
//        }
//    }

}
