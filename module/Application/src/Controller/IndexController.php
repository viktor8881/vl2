<?php


namespace Application\Controller;


use Analysis\Service\MoexAnalysisService;
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

    /** @var MoexAnalysisService */
    private $analisysService;

    /**
     * IndexController constructor.
     * @param ExchangeManager $exchangeManager
     * @param CourseManager $courseManager
     * @param MovingAverage $movingAverage
     * @param MoexAnalysisService $analisysService
     */
    public function __construct(ExchangeManager $exchangeManager, CourseManager $courseManager, MovingAverage $movingAverage, MoexAnalysisService $analisysService)
    {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->movingAverage = $movingAverage;
        $this->analisysService = $analisysService;
    }


    public function indexAction()
    {
        $result = [];
        $item = null;

        $exchanges = $this->exchangeManager->fetchAllFavorite();
        if (count($exchanges)) {
            $result = $this->analisysService->listOrderWeight(
                $exchanges,
                $this->params()->fromQuery('refresh', true));

            $id = $this->params()->fromRoute('id', reset($exchanges)->getId());
            $item = $this->exchangeManager->get($id);
        }

        return [
            'exchanges'       => $result,
            'currentExchange' => $item
        ];
    }

    public function aboutAction()
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
}
