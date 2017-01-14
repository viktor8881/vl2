<?php


namespace Course\Controller;

use Core\Entity\CriterionCollection;
use Course\Entity\Criteria\CriterionExchange;
use Course\Entity\Criteria\CriterionPercent;
use Course\Entity\Criteria\CriterionPeriod;
use Course\Service\CourseManager;
use Exchange\Service\ExchangeManager;
use Course\Validator\InputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var CourseManager */
    private $courseManager;

    private static $DATA_DEF;


    public function __construct(ExchangeManager $exchangeManager,
        CourseManager $courseManager
    ) {
        $this->exchangeManager = $exchangeManager;
        $this->courseManager = $courseManager;
        $dateNow = new \DateTime();
        self::$DATA_DEF = $dateNow->sub(new \DateInterval('P1Y'))->format(
            'd.m.Y'
        );
    }

    public function currencyAction()
    {
        $data = [
            'id'         => $this->params()->fromRoute('id', 5),
            'date_start' => $this->params()->fromQuery(
                'start', self::$DATA_DEF
            ),
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

        return new ViewModel(
            ['exchanges'       => $this->exchangeManager->fetchAllCurrency(),
             'currentExchange' => $currentItem,
             'period'          => ['start' => $values['date_start'],
                                   'end'   => $values['date_end']],
             'courses' => $this->courseManager->fetchAllByCriteria($criteria)]
        );
    }


    public function metalAction()
    {
        $data = [
            'id'         => $this->params()->fromRoute('id', 1),
            'date_start' => $this->params()->fromQuery(
                'start', self::$DATA_DEF
            ),
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

        return new ViewModel(
            ['exchanges'       => $this->exchangeManager->fetchAllMetal(),
             'currentExchange' => $metalItem,
             'period'          => ['start' => $values['date_start'],
                                   'end'   => $values['date_end']],
             'courses' => $this->courseManager->fetchAllByCriteria($criteria)]
        );
    }
}