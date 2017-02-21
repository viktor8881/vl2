<?php
namespace Cron\Controller;

use Base\Service\Date;
use Course\Service\CourseManager;
use Course\Service\CourseService;
use Exchange\Service\ExchangeManager;
use Exchange\Entity\Exchange;
use Zend\Mvc\Controller\AbstractActionController;

class CourseController extends AbstractActionController
{

    const COUNT_RUN_AT_TIME = 100;


    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var CourseManager */
    private $courseManager;

    /** @var CourseService */
    private $courseService;

    /** @var string */
    private $tmpDir;

    public function __construct(ExchangeManager $exchangeManager,
        CourseManager $courseManager, CourseService $courseService, $tmpDir
    ) {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->courseService = $courseService;
        $this->tmpDir = $tmpDir;
    }

    private function tmpAction()
    {
        $dateNow = new Date('12.02.2017');
        $fileName = $this->tmpDir . 'date-metal.tmp';
        if (!file_exists($fileName)) {
            exit;
        }
        $i = 0;
        $flag = true;
        while ($flag) {
            if (++$i > self::COUNT_RUN_AT_TIME) {
                $flag = false;
                break;
            }
            // находим дату
            $date = new Date(file_get_contents($fileName));
            if ($this->courseManager->hasByDate($date)) {
                $date->add(new \DateInterval('P1D'));
                file_put_contents($fileName, $date->formatDMY());
                continue;
            }
            if ($date->compareDate($dateNow) == 1) {
                rename($fileName, $this->tmpDir . '_date-metal.tmp');
                $flag = false;
                break;
            }
            //===================================================================
            $this->receiveAction($date);
            //===================================================================
            $date->add(new \DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }

        return $this->getResponse();
    }

    public function receiveAction($date = null)
    {
        if (is_null($date)) {
            $date = new \DateTime('15.02.2017');
        }
        if (!$this->courseManager->hasByDate($date)) {
            try {
                /** @var Exchange[] $exchanges */
                $exchanges = $this->exchangeManager->fetchAll();
                $listCourse = $this->courseService->receiveByDateToListCourse(
                    $date, $exchanges
                );
                $this->courseManager->insertList($listCourse);
                echo 'ok!';
            } catch (\Exception $exception) {
                $this->getResponse()->setStatusCode(500);
                return;
            }
        }
        return $this->getResponse();
    }
}
