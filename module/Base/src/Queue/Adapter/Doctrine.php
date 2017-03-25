<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Queue
 */

namespace Base\Queue\Adapter;

use Base\Queue\Adapter\Doctrine\Service\QueueManager;
use ZendQueue\Adapter\AbstractAdapter;
use ZendQueue\Exception;
use ZendQueue\Message;
use ZendQueue\Queue;

/**
 * Class for using connecting to a Zend_DB-based queuing system
 *
 * @category   Zend
 * @package    Zend_Queue
 * @subpackage Adapter
 */
class Doctrine extends AbstractAdapter
{

    const MANAGER_NAME = 'manager_name';

    /**
     * @var QueueManager
     */
    private $queueManager;

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * Doctrine constructor.
     *
     * @param array|\Traversable $options
     * @param Queue|null         $queue
     */
    public function __construct($options, Queue $queue = null)
    {
        parent::__construct($options, $queue);
        if (!isset($this->_options['options'][self::MANAGER_NAME])) {
            throw new Exception\InvalidArgumentException('Options array item: must be set');
        }
        $this->queueManager = $this->_options['options'][self::MANAGER_NAME];
    }


    /**
     * Does a queue already exist?
     *
     * Throws an exception if the adapter cannot determine if a queue exists.
     * use isSupported('isExists') to determine if an adapter can test for
     * queue existance.
     *
     * @param  string $name
     *
     * @return boolean
     */
    public function isExists($name)
    {
        $queue = $this->queueManager->getByName($name);
        return ($queue) ? true : false;
    }

    /**
     * Create a new queue
     *
     * Visibility timeout is how long a message is left in the queue "invisible"
     * to other readers.  If the message is acknowleged (deleted) before the
     * timeout, then the message is deleted.  However, if the timeout expires
     * then the message will be made available to other queue readers.
     *
     * @param  string  $name    queue name
     * @param  integer $timeout default visibility timeout
     */
    public function create($name, $timeout = null)
    {
        if ($this->isExists($name)) {
            return false;
        }

        if (is_null($timeout)) {
            $timeout = self::CREATE_TIMEOUT_DEFAULT;
        }

        $queue = $this->queueManager->createEntity();
        $queue->setName($name);
        $queue->setTimeout((int)$timeout);

        try {
            $this->queueManager->insert($queue);
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                $e->getMessage(), $e->getCode(), $e
            );
        }
    }

    /**
     * Delete a queue and all of it's messages
     *
     * Returns false if the queue is not found, true if the queue exists
     *
     * @param  string $name queue name
     *
     * @return boolean
     */
    public function delete($name)
    {
        $queue = $this->queueManager->getByName($name);
        if (!$queue) {
            return false;
        }
        try {
            $this->queueManager->delete($queue);
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                $e->getMessage(), $e->getCode(), $e
            );
        }
        return true;
    }

    /**
     * Get an array of all available queues
     *
     * Not all adapters support getQueues(), use isSupported('getQueues')
     * to determine if the adapter supports this feature.
     *
     * @return array
     */
    public function getQueues()
    {
        $result = [];
        /** @var \Base\Queue\Adapter\Doctrine\Entity\Queue $queue */
        foreach ($this->queueManager->fetchAll() as $queue) {
            $result[] = $queue->getName();
        }
        return $result;
    }

    /**
     * Return the approximate number of messages in the queue
     *
     * @param  \ZendQueue\Queue $queue
     *
     * @return integer
     * @throws \ZendQueue\Exception
     */
    public function count(Queue $queue = null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }
        return $this->queueManager->countMessageByQueue($this->getQueueByName($queue->getName()));
    }

    /********************************************************************
     * Messsage management functions
     *********************************************************************/

    /**
     * Send a message to the queue
     *
     * @param  string           $message Message to send to the active queue
     * @param  \ZendQueue\Queue $queue
     *
     * @return \ZendQueue\Message
     * @throws \ZendQueue\Exception
     */
    public function send($message, Queue $queue = null)
    {
        if ($queue === null) {
            $queue = $this->_queue;
        }

        if (!$this->isExists($queue->getName())) {
            throw new Exception\QueueNotFoundException(
                'Queue does not exist:' . $queue->getName()
            );
        }

        $messageEntity = $this->queueManager->createMessageEntity();
        $messageEntity->setId(md5(uniqid(rand(), true)))
            ->setBody($message)
            ->setMd5(md5($message))
            ->setHandle(null)
            ->setCreated(time())
            ->setQueue($this->queueManager->getByName($queue->getName()));

        try {
            $this->queueManager->insertMessage($messageEntity);
        } catch (\Exception $exc) {
            throw new Exception\RuntimeException($exc->getMessage(), $exc->getCode(), $exc);
        }

        $options = array(
            'queue' => $queue,
            'data'  => $messageEntity->toArray(),
        );
        $classname = $queue->getMessageClass();
        return new $classname($options);
    }

    /**
     * Get messages in the queue
     *
     * @param  integer          $maxMessages Maximum number of messages to return
     * @param  integer          $timeout     Visibility timeout for these messages
     * @param  \ZendQueue\Queue $queue
     *
     * @return \ZendQueue\Message\MessageIterator
     */
    public function receive($maxMessages = null, $timeout = null, Queue $queue = null) {
        if ($maxMessages === null) {
            $maxMessages = 1;
        }
        if ($timeout === null) {
            $timeout = self::RECEIVE_TIMEOUT_DEFAULT;
        }
        if ($queue === null) {
            $queue = $this->_queue;
        }

        $msgs      = array();
        $microtime = microtime(true); // cache microtime
        $this->queueManager->beginTransaction();
        try {
            if ( $maxMessages > 0 ) {
                $messages = $this->queueManager->fetchAllMessageByQueueAndHandle($this->getQueueByName($queue->getName()), $timeout, $microtime, $maxMessages);
                foreach ($messages as $mess) {
                    // setup our changes to the message
                    $mess->setHandle(md5(uniqid(rand(), true)))
                        ->setTimeout($microtime);

                    $count = $this->queueManager->updateMessageByHandle($mess, $timeout, $microtime);

                    // we check count to make sure no other thread has gotten
                    // the rows after our select, but before our update.
                    if ($count > 0) {
                        $msgs[] = $mess->toArray();
                    }
                }
                $this->queueManager->commit();
            }
        } catch (\Exception $e) {
            $this->queueManager->rollBack();
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $options = array(
            'queue'        => $queue,
            'data'         => $msgs,
            'messageClass' => $queue->getMessageClass(),
        );

        $classname = $queue->getMessageSetClass();
        return new $classname($options);
    }

    /**
     * Delete a message from the queue
     *
     * Returns true if the message is deleted, false if the deletion is
     * unsuccessful.
     *
     * @param  \ZendQueue\Message $message
     *
     * @return boolean
     * @throws \ZendQueue\Exception
     */
    public function deleteMessage(Message $message)
    {
        return $this->queueManager->deleteMessageByHandle($message->handle);
    }

    /**
     * Return a list of queue capabilities functions
     *
     * $array['function name'] = true or false
     * true is supported, false is not supported.
     *
     * @return array
     */
    public function getCapabilities()
    {
        return array(
            'create'        => true,
            'delete'        => true,
            'send'          => true,
            'receive'       => true,
            'deleteMessage' => true,
            'getQueues'     => true,
            'count'         => true,
            'isExists'      => true,
        );
    }

    // =========================================================================

    private function getQueueByName($name)
    {
        return $this->queueManager->getByName($name);
    }
}
