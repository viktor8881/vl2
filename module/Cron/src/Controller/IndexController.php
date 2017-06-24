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

    public function indexAction()
    {
        $queue = $this->queue;

//        $queue->send(self::TASK_SEND_MESSAGE); exit;

        $messages = $queue->receive();
        foreach ($messages as $message) {
            $body = $message->body;
            switch ($body) {
                case self::TASK_RECEIVE_DATA:
                    $response = $this->forward()->dispatch(CourseController::class, array('controller' => CourseController::class, 'action'=>'receive'));
                    if ($response->getStatusCode() == 200 ) {
                        $queue->send(self::TASK_CACHE_DATA);
                    } else {
                        $queue->send(self::TASK_RECEIVE_DATA);
                    }
                    break;
                case self::TASK_CACHE_DATA:
                    $response = $this->forward()->dispatch(CacheCourseController::class, array('controller' => CacheCourseController::class, 'action'=>'fill-cache'));
                    if ($response->getStatusCode() == 200 ) {
                        $queue->send(self::TASK_ANALYSIS);
                    } else {
                        $queue->send(self::TASK_CACHE_DATA);
                    }
                    break;
                case self::TASK_ANALYSIS:
                    $response = $this->forward()->dispatch(AnalysisController::class, array('controller' => AnalysisController::class, 'action' =>'index'));
                    if ($response->getStatusCode() == 200 ) {
                        $queue->send(self::TASK_SEND_MESSAGE);
                    } else {
                        $queue->send(self::TASK_ANALYSIS);
                    }
                    break;
                case self::TASK_SEND_MESSAGE:
                    $response = $this->forward()->dispatch(MessageController::class, array('controller' => MessageController::class, 'action' =>'send-message'));
                    if ($response->getStatusCode() == 200 ) {
                        $queue->send(self::TASK_RECEIVE_DATA);
                    } else {
                        $queue->send(self::TASK_SEND_MESSAGE);
                    }
                    break;
                default:
                    throw new \Exception('unknown type task.');
                    break;
            }
            $queue->deleteMessage($message);
        }
        return $this->getResponse();
    }

}
