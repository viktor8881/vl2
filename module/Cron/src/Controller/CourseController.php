<?php
namespace Cron\Controller;

use Course\Service\CourseManager;
use Course\Service\CourseService;
use Exchange\Service\ExchangeManager;
use Exchange\Entity\Exchange;
use Zend\Mvc\Controller\AbstractActionController;

class CourseController extends AbstractActionController
{

    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var CourseManager */
    private $courseManager;

    public function __construct(ExchangeManager $exchangeManager,
                                CourseManager $courseManager, CourseService $courseService)
    {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->courseService = $courseService;
    }

    public function receiveAction()
    {
        $date = new \DateTime('09.02.2017');
        if (!$this->courseManager->hasByDate($date)) {
            try {
                /** @var Exchange[] $exchanges */
                $exchanges = $this->exchangeManager->fetchAllIndexCode();
                $listCourse = $this->courseService->receiveByDateToListCourse($date, $exchanges);
                $this->courseManager->insertList($listCourse);

                // tasks to queue
//                    $queue = $this->getQueue('analysis');
//                    $queue->sendFillData(true);
                echo 'ok!';
            } catch (\Exception $exception) {
                $this->getResponse()->setStatusCode(500);
                return;
            }
        }
        return $this->getResponse();
    }
}
