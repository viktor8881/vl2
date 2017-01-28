<?php


/**
 * Class Model_CardCurrencyBalance
 */
class Model_CardCurrencyBalance {

    /**
     * @var
     */
    private $balance;
    /**
     * @var
     */
    private $sumInvest;
    /**
     * @var
     */
    private $currentCourse;

    /**
     * @var
     */
    private $_sumPay;
    
    /**
     * 
     * @return BalanceCurrency_Model
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getValueBalance() {
        $balance = $this->getBalance();
        return ($balance)?$balance->getBalance():0;
    }

    /**
     * @return mixed
     */
    public function getSumInvest() {
        return $this->sumInvest;
    }

    /**
     * 
     * @return CourseCurrency_Model
     */
    public function getCurrentCourse() {
        return $this->currentCourse;
    }

    /**
     * @return string
     */
    public function getValueCurrentCourse() {
        $course = $this->getCurrentCourse();
        return ($course)?$course->getValueForOne():'0';
    }

    /**
     * @param BalanceCurrency_Model $balance
     *
     * @return $this
     */
    public function setBalance(BalanceCurrency_Model $balance) {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @param $sumInvest
     *
     * @return $this
     */
    public function setSumInvest($sumInvest) {
        $this->sumInvest = $sumInvest;
        return $this;
    }

    /**
     * @param CourseCurrency_Model $currentCourse
     *
     * @return $this
     */
    public function setCurrentCourse(CourseCurrency_Model $currentCourse) {
        $this->currentCourse = $currentCourse;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyName() {
        $balance = $this->getBalance();
        if ($balance) {
            return $balance->getCurrencyName();
        }
        return '';
    }
    
    /**
     * Сумма продажи по текущему курсу
     * @return float
     */
    public function getSumPay() {
        if (is_null($this->_sumPay)) {
            $this->_sumPay = $this->getValueBalance() * $this->getValueCurrentCourse();
        }
        return $this->_sumPay;
    }

    /**
     * @return float
     */
    public function diffSumInvestToPercent() {
        return $this->getSumInvest()*100/$this->getSumPay();
    }

    /**
     * @return float
     */
    public function diffSumInvest() {
        return $this->getSumPay()-$this->getSumInvest();
    }

    /**
     * @return bool
     */
    public function isInvestUp() {
        return $this->diffSumInvest() >= 0;
    }

    /**
     * @return bool
     */
    public function isInvestDown() {
        return $this->diffSumInvest() < 0;
    }
    
}
