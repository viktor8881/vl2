<?php


namespace Application\Controller;


use Analysis\Service\MovingAverage;
use Base\Entity\CriterionCollection;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{

    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var CourseManager */
    private $courseManager;

    /** @var MovingAverage */
    private $movingAverage;

    /**
     * IndexController constructor.
     * @param ExchangeManager $exchangeManager
     * @param CourseManager $courseManager
     * @param MovingAverage $movingAverage
     */
    public function __construct(ExchangeManager $exchangeManager, CourseManager $courseManager, MovingAverage $movingAverage)
    {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->movingAverage = $movingAverage;
    }


    public function indexAction()
    {
        $dateStart = new \DateTime();
        $dateStart->sub(new \DateInterval('P4M'));

        $criteria = new CriterionCollection();
        $criteria->append(new CriterionExchange(1));
        $criteria->append(
            new CriterionPeriod([$dateStart, new \DateTime()])
        );

        $courses = $this->courseManager->fetchAllByCriterions($criteria);
        return new ViewModel([
            'courses' => $courses,
            'movingAverage1'   => $this->movingAverage->listAvgByCourses($courses, 12),
            'movingAverage2'   => $this->movingAverage->listAvgByCourses($courses, 25)
            ]);
    }

    public function aboutAction()
    {
        return new ViewModel();
    }
}
