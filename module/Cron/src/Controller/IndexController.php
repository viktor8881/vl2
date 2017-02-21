<?php
namespace Cron\Controller;

use Base\Queue\Adapter\Doctrine;
use Base\Queue\Adapter\Doctrine\Service\QueueManager;
use Base\Service\Date;
use Task\Service\TaskManager;
use Zend\Mvc\Controller\AbstractActionController;
use ZendQueue\Queue;

class IndexController extends AbstractActionController
{

    const TASK_RECEIVE_DATA = 'receive_data';
    const TASK_CACHE_DATA = 'cache_data';
    const TASK_ANALYSIS = 'analysis';
    const TASK_SEND_MESSAGE = 'send_message';

    /**
     * @var Queue
     */
    private $queue;
    /**
     * @var
     */
    private $analysisService;

    public function __construct(Queue $queue, AnalysisService $analysisService, TaskManager $taskManager)
    {
        $this->queue = $queue;
        $this->analysisService = $analysisService;
        $this->taskManager = $taskManager;
    }

    public function indexAction()
    {
        $dateNow = new Date();
        $queue = $this->queue;
//        $queue->send(self::TASK_RECEIVE_DATA); exit;

//        return $this->forward()->dispatch(CourseController::class, array('action'=>'receive'));
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
                    $this->taskAnalysis($dateNow);
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
        echo 'ok';
        return $this->getResponse();
    }

    private function taskAnalysis(Core_Date $dateNow) {
        $count = 0;
        // считываем настройки выполнения анализа
        $tasks = $this->taskManager->fetchAll();
        foreach ($tasks as $task) {
            $count += $this->analysisService->runByTask($task, $dateNow);
        }
        return $count;
    }
}
