<?php

namespace Base\Queue\Adapter\Doctrine\Entity;

use Base\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message" )
 */
class Message extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="message_id", type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Queue")
     * @ORM\JoinColumn(name="queue_id", referencedColumnName="queue_id")
     */
    protected $queue;
    /** @ORM\Column(name="handle", type="string", length=32) */
    protected $handle;
    /** @ORM\Column(name="body", type="string", length=8192) */
    protected $body;
    /** @ORM\Column(name="md5", type="string", length=32) */
    protected $md5;
    /** @ORM\Column(name="timeout", type="decimal", precision=4, scale=14) */
    protected $timeout;
    /** @ORM\Column(name="created", type="integer") */
    protected $created;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return Message
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return null|integer
     */
    public function getQueueId()
    {
        if ($this->queue) {
            return $this->queue->getId();
        }
        return null;
    }

    /**
     * @param Queue $queue
     *
     * @return Message
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return bool
     */
    public function isNullHandle()
    {
        return is_null($this->getHandle());
    }

    /**
     * @param mixed $handle
     *
     * @return Message
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     *
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * @param mixed $md5
     *
     * @return Message
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param mixed $timeout
     *
     * @return Message
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     *
     * @return Message
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    public function toArray()
    {
        return ['message_id'=>$this->getId(),
        'queue_id' => $this->getQueueId(),
        'handle' => $this->getHandle(),
        'body' => $this->getBody(),
        'md5' => $this->getMd5(),
        'timeout' => $this->getTimeout(),
        'created' => $this->getCreated(),
        ];
    }

}