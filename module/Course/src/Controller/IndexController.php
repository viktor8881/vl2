<?php
namespace Course\Controller;

use Analysis\Service\MoexAnalysisService;
use Analysis\Service\MovingAverage;
use Base\Entity\CriterionCollection;
use Course\Entity\Criterion\CriterionExchange;
use Course\Entity\Criterion\CriterionPeriod;
use Course\Service\CourseManager;
use Course\Validator\InputFilter;
use Exchange\Service\ExchangeManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    const DURING_MOVING_AVERAGE = 9;

    /** @var ExchangeManager */
    private $exchangeManager;
    /** @var CourseManager */
    private $courseManager;
    /** @var MovingAverage */
    private $movingAverage;
    /** @var MoexAnalysisService */
    private $analisysService;


    private static $DATA_DEF;


    public function __construct(ExchangeManager $exchangeManager, CourseManager $courseManager, MovingAverage $movingAverage, MoexAnalysisService $analisysService)
    {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $this->movingAverage = $movingAverage;
        $this->analisysService = $analisysService;
        $dateNow = new \DateTime();
        self::$DATA_DEF = $dateNow->sub(new \DateInterval('P6M'))->format('d.m.Y');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function currencyAction()
    {
        $data = [
            'id'         => $this->params()->fromRoute('id', 5),
            'date_start' => $this->params()->fromQuery('start', self::$DATA_DEF),
            'date_end'   => $this->params()->fromQuery('end', date('d.m.Y'))
        ];
        $fInput = new InputFilter($data);
        if (!$fInput->isValid()) {
            throw new \Exception('Wrong input parameters.');
        }
        $values = $fInput->getValues();

        $currentItem = $this->exchangeManager->getCurrencyById($values['id']);
        if (!$currentItem) {
            throw new \Exception('Not found currency.');
        }

        $criteria = new CriterionCollection();
        $criteria->append(new CriterionExchange($values['id']));
        $criteria->append(
            new CriterionPeriod(
                [new \DateTime($values['date_start']),
                 new \DateTime($values['date_end'])]
            )
        );

        $courses = $this->courseManager->fetchAllByCriterions($criteria);
        return ['exchanges'       => $this->exchangeManager->fetchAllCurrency(),
                'currentExchange' => $currentItem,
                'period'          => ['start' => $values['date_start'], 'end'   => $values['date_end']],
                'courses'         => $courses,
                'movingAverage1'   => $this->movingAverage->listAvgByCourses($courses, self::DURING_MOVING_AVERAGE)
                ];
    }

    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function metalAction()
    {
        $data = [
            'id'         => $this->params()->fromRoute('id', 1),
            'date_start' => $this->params()->fromQuery('start', self::$DATA_DEF),
            'date_end'   => $this->params()->fromQuery('end', date('d.m.Y'))
        ];
        $fInput = new InputFilter($data);
        if (!$fInput->isValid()) {
            throw new \Exception('Wrong input parameters.');
        }
        $values = $fInput->getValues();

        $metalItem = $this->exchangeManager->getMetalById($values['id']);
        if (!$metalItem) {
            throw new \Exception('Not found metal.');
        }

        $criteria = new CriterionCollection();
        $criteria->append(new CriterionExchange($values['id']));
        $criteria->append(
            new CriterionPeriod(
                [new \DateTime($values['date_start']),
                 new \DateTime($values['date_end'])]
            )
        );
        $courses = $this->courseManager->fetchAllByCriterions($criteria);

        $view = new ViewModel(
            ['exchanges'       => $this->exchangeManager->fetchAllMetal(),
             'currentExchange' => $metalItem,
             'period'          => ['start' => $values['date_start'], 'end'   => $values['date_end']],
             'courses'         => $courses,
             'movingAverage1'   => $this->movingAverage->listAvgByCourses($courses, 9),
             'movingAverage2'   => $this->movingAverage->listAvgByCourses($courses, 14),
            ]
        );
//        $view->setTemplate('course/index/currency.phtml');
        return $view;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function stockAction()
    {
        $id = $this->params()->fromRoute('id', 13);

        $item = $this->exchangeManager->getStockById($id);
        if (!$item) {
            throw new \Exception('stock not found.');
        }
        $result = $this->analisysService->listOrderWeight($this->exchangeManager->fetchAllStock());

        return [
            'exchanges'       => $result,
            'currentExchange' => $item
        ];
    }

}
