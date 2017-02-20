<?php
namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendQueue\Queue;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $options = array(
            'name'             => 'def_queue',
        );

        $queue = new Queue('ArrayAdapter', $options);
        $queue->send('first1');
        $queue->send('first2');
        $queue->send('first3');
        var_dump($queue->count());
        pr($queue);

        $messages = $queue->receive(5);

        foreach ($messages as $i => $message) {
            echo $message->body, "\n";

            // Сообщение обработано, его можно удалить
            $queue->deleteMessage($message);
        }
        $queue->send('first4');
        var_dump($queue->count());
        pr($queue);
        echo 'Hello world';
        return $this->getResponse();
    }
}
