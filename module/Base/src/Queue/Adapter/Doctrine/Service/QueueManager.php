<?php

namespace Base\Queue\Adapter\Doctrine\Service;

use Base\Entity\AbstractCriterion;
use Base\Entity\AbstractOrder;
use Base\Entity\CriterionCollection;
use Base\Queue\Adapter\Doctrine\Entity\Criterion\QueueId;
use Base\Queue\Adapter\Doctrine\Entity\Criterion\QueueName;
use Base\Queue\Adapter\Doctrine\Entity\Message;
use Base\Queue\Adapter\Doctrine\Entity\Queue;
use Base\Service\AbstractManager;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\QueryBuilder;


class QueueManager extends AbstractManager
{

    const ENTITY_MESSAGE = Message::class;

    /**
     * @param $name
     * @return \Base\Entity\AbstractEntity|null
     */
    public function getByName($name)
    {
        $criterions = new CriterionCollection();
        $criterions->append(new QueueName($name));
        return $this->getByCriterions($criterions);
    }

    /**
     * @param Queue $queue
     * @return int
     */
    public function countMessageByQueue(Queue $queue)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(' . self::ENTITY_MESSAGE . '.id) as count_item')
            ->from(self::ENTITY_MESSAGE, self::ENTITY_MESSAGE)
            ->andWhere(self::ENTITY_MESSAGE.'.queue = :queue')
            ->setParameter('queue',  $queue);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Message $message
     * @return Message
     */
    public function insertMessage(Message $message)
    {
        $this->em->persist($message);
        $this->em->flush();
        return $message;
    }

    /**
     * @param Queue $queue
     * @param       $timeout
     * @param       $handle
     * @param int   $maxResult
     *
     * @return Message[]
     */
    public function fetchAllMessageByQueueAndHandle(Queue $queue, $timeout, $handle, $maxResult = 50)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select(self::ENTITY_MESSAGE)
            ->from(self::ENTITY_MESSAGE, self::ENTITY_MESSAGE);
        $qb->andWhere(self::ENTITY_MESSAGE.'.queue = :queue')
            ->setParameter('queue',  $queue)
            ->andWhere(self::ENTITY_MESSAGE.'.handle IS NULL OR ' . self::ENTITY_MESSAGE.'.timeout+' . (int)$timeout . ' <  :handle')
            ->setParameter('handle',  $handle);
        $qb->setFirstResult(0)
            ->setMaxResults($maxResult);

        $query = $qb->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE);
        return $query->getResult();
    }

    /**
     * @param Message $message
     * @param         $timeout
     * @param         $handle
     *
     * @return int
     */
    public function updateMessageByHandle(Message $message, $timeout, $handle)
    {
        $dql = 'UPDATE ' . self::ENTITY_MESSAGE . ' m SET m.handle = \''.$message->getHandle().'\', 
                m.body = \''.$message->getBody().'\',
                m.md5 = \''.$message->getMd5().'\',
                m.timeout = '.$message->getTimeout().',
                m.created = '.$message->getCreated().' 
                WHERE m.id = ' . $message->getId() . ' and (m.handle IS NULL OR m.timeout+' . (int)$timeout . ' <  '.(int)$handle .')';
        $q = $this->em->createQuery($dql);
        return $q->execute();
    }


    /**
     * @param $id
     * @return null|Message
     */
    public function getMessageForUpdate($id)
    {
        return $this->em->getRepository(Message::class)->find($id, LockMode::PESSIMISTIC_WRITE);
    }

    /**
     * @param $handle
     *
     * @return \Base\Entity\AbstractEntity|null
     */
    public function getMessageByHandleForUpdate($handle)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select(self::ENTITY_MESSAGE)
            ->from(self::ENTITY_MESSAGE, self::ENTITY_MESSAGE);
        $qb->andWhere(self::ENTITY_MESSAGE.'.handle = :handle')
            ->setParameter('handle',  $handle);

        $result = $qb->getQuery()->getResult();
        if ($result) {
            return current($result);
        }
        return null;
    }

    /**
     * @param $handle
     * @return bool
     */
    public function deleteMessageByHandle($handle)
    {
        $mess = $this->getMessageByHandleForUpdate($handle);
        if (!$mess) {
            return false;
        }
        $this->em->remove($mess);
        $this->em->flush();
        return true;
    }

    /**
     * @param AbstractCriterion $criterion
     * @param QueryBuilder  $qb
     * @return mixed|void
     */
    protected function addCriterion(AbstractCriterion $criterion, QueryBuilder $qb) {
        switch (get_class($criterion)) {
            case QueueName::class:
                $qb->andWhere($this->entityName.'.name IN (:name)')
                    ->setParameter('name',  $criterion->getValues());
                break;
            case QueueId::class:
                $qb->andWhere($this->entityName.'.id IN (:id)')
                    ->setParameter('id',  $criterion->getValues());
                break;
            default:
                break;
        }
    }

    /**
     * @param array $values
     *
     * @return Message
     */
    public function createMessageEntity(array $values = [])
    {
        return new Message($values);
    }

    /**
     * @param AbstractOrder $order
     * @param QueryBuilder  $qb
     *
     * @return mixed|void
     */
    protected function addOrder(AbstractOrder $order, QueryBuilder $qb)
    {

    }

}