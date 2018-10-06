<?php
namespace Cron\Controller;

use Base\Queue\Queue;
use Exchange\Service\ExchangeManager;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    const TASK_RECEIVE_DATA     = 'receive_data';
    const TASK_CACHE_DATA       = 'cache_data';
    const TASK_ANALYSIS         = 'analysis';
    const TASK_SEND_MESSAGE     = 'send_message';

    /** @var Queue */
    private $queue;
    /** @var Queue */
    private $moexQueue;
    /** @var Logger */
    private $logger;

    public function __construct(Queue $queue, Queue $moexQueue, Logger $logger)
    {
        $this->queue = $queue;
        $this->moexQueue = $moexQueue;
        $this->logger = $logger;
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
                        $queue->send(self::TASK_RECEIVE_DATA);
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

    public function moexAction()
    {
        foreach (ExchangeManager::MAP_MOEX_SECID as $exchangeId) {
            $mainLogMess = 'excangeId => ' . $exchangeId;
            $this->logger->info($mainLogMess);
            $response = $this->forward()->dispatch(
                MoexController::class,
                [   'controller' => MoexController::class,
                    'action' => 'index',
                    'exchangeId' => $exchangeId
                ]
            );
            $this->logger->info($mainLogMess .' data from moex received. Status=>' . $response->getStatusCode());
            if ($response->getStatusCode() == 200 ) {
                $response = $this->forward()->dispatch(
                    MoexCacheCourseController::class,
                    [   'controller' => MoexCacheCourseController::class,
                        'action' => 'index',
                        'exchangeId' => $exchangeId
                    ]
                );
                $this->logger->info($mainLogMess .' insert cache course. Status=>' . $response->getStatusCode());
                if ($response->getStatusCode() == 200 ) {
                    $response = $this->forward()->dispatch(
                        MoexAnalysisController::class,
                        [   'controller' => MoexAnalysisController::class,
                            'action' => 'index',
                            'exchangeId' => $exchangeId
                        ]
                    );
                    $this->logger->info($mainLogMess .' analysis created. Status=>' . $response->getStatusCode());
                    if ($response->getStatusCode() == 200 ) {
                        $response = $this->forward()->dispatch(
                            MoexMessageController::class,
                            [   'controller' => MoexMessageController::class,
                                'action' => 'index',
                                'exchangeId' => $exchangeId
                            ]
                        );
                    }
                    $this->logger->info($mainLogMess .' mail sent. Status=>' . $response->getStatusCode());
                }
            }
        }
        return $this->getResponse();
    }


//    public function moexCacheAction()
//    {
//        /** @var Queue $queue */
//        $queue = $this->moexQueue;
//
//        $queue->createQueue('moex-cache');
//        $queue->setOption(Queue::NAME, 'moex-cache');
//
//        foreach ($queue->receiveAsArray(10) as $message) {
//            $exchangeId = $message->body['exchangeId'];
//            $response = $this->forward()->dispatch(
//                MoexAnalysisController::class,
//                [   'controller' => MoexAnalysisController::class,
//                    'action' => 'index',
//                    'exchangeId' => $exchangeId
//                ]
//            );
//            if ($response->getStatusCode() == 200 ) {
//                $queue->createQueue('moex-analisys');
//                $queue->setOption(Queue::NAME, 'moex-analisys');
//                $queue->sendArray(['exchangeId' => $exchangeId]);
//            } else {
//                $queue->deleteMessage($message);
//                $queue->sendArray(['exchangeId' => $exchangeId]);
//            }
//        }
//        return $this->getResponse();
//    }

}
