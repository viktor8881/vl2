<?php

namespace Base\Queue;


class Queue extends \ZendQueue\Queue
{

    /**
     * @param array $data
     * @return \ZendQueue\Message
     */
    public function sendArray(array $data)
    {
        return $this->send(json_encode($data));
    }

    /**
     * @param null $maxMessages
     * @param null $timeout
     * @return \ZendQueue\Message\MessageIterator
     */
    public function receiveBodyAsArray($maxMessages = null, $timeout = null)
    {
        $result = parent::receive($maxMessages, $timeout);
        foreach ($result as $message) {
            $message->body = json_decode($message->body, true);
        }
        return $result;
    }

}