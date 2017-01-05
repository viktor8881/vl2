<?php


namespace Course\Controller;

use Course\Entity\Criteria\ExchangeId;
use Course\Entity\Criteria\Period;
use Course\Service\CacheCourseManager;
use Doctrine\Common\Collections\Criteria;
use Exchange\Entity\Exchange;
use Exchange\Service\ExchangeManager;
use Course\Validator\InputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayObject;
use Zend\Stdlib\PriorityList;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /** @var ExchangeManager */
    private $exchangeManager;

    /** @var CacheCourseManager */
    private $cacheCourseManager;

    private static $DATA_DEF;


    public function __construct(ExchangeManager $exchangeManager, $cacheCourseManager)
    {
        $this->exchangeManager = $exchangeManager;
        $this->cacheCourseManager = $cacheCourseManager;
        $dateNow = new \DateTime();
        self::$DATA_DEF = $dateNow->sub(new \DateInterval('P1Y'))->format('d.m.Y');
    }

//    public function currencyAction()
//    {
//        if (!($current = $this->exchangeManager->getCurrencyById((int)$this->params()->fromRoute('id')))) {
//            throw new \Exception('Currency not found.');
//        }
//        $percent = (float)$this->params()->fromQuery('percent', 0.2);
//        $dateStart = $this->params()->fromQuery('start', self::$DATA_DEF);
//        $dateEnd = $this->params()->fromQuery('end', date('d.m.Y'));
//
//        $currencies = $this->exchangeManager->fetchAllCurrency();
//
//        if (!StaticValidator::execute($dateStart, 'Date', ['format'=>'d.m.Y']) or !Zend_Validate::is($dateEnd, 'Date', array(), 'Core_Validate')) {
//            throw new \Exception('Wrong format date or wrong period.');
//        }
//
//        if (!Zend_Validate::is($dateStart, 'Date', array(), 'Core_Validate') or !Zend_Validate::is($dateEnd, 'Date', array(), 'Core_Validate')) {
//            throw new \Exception('Wrong format date or wrong period.');
//        }
//        return new ViewModel(['period' => ['start' => $dateStart, 'end' => $dateEnd],
//            'currencies' => $currencies,
//            'currentItem' => $current,
//            'courses' => $this->cacheCourseManager->fetchAllByPeriodByCodeByPercent(new \DateTime($dateStart), new \DateTime($dateEnd), $current->getCode(), $percent)]);
//
//
//
//    }

    public function currencyAction()
    {
        $data = [
            'id' => $this->params()->fromRoute('id'),
            'date_start' => $this->params()->fromQuery('start', self::$DATA_DEF),
            'date_end' => $this->params()->fromQuery('end', date('d.m.Y')),
            'percent' => $this->params()->fromQuery('percent', 0.2),
        ];
        $fInput = new InputFilter($data);
        if (!$fInput->isValid()) {
            throw new \Exception('Wrong input parameters.');
        }
        if(strtotime($data['date_end']) <= strtotime($data['date_start'])) {
            throw new \Exception('Wrong period.');
        }
        $values = $fInput->getValues();

        $criteria = new ArrayObject();
        $criteria->append(new ExchangeId($values['id']));
        $criteria->append(new Period(new \DateTime($values['date_start']), new \DateTime($values['date_end'])));
//            ->append(new ExchangePercent());
        $values['courses']
            = $this->cacheCourseManager->fetchAllByCriteria($criteria);
        $values['currencies'] = $this->exchangeManager->fetchAllCurrency();
        return new ViewModel($data);
    }
}
