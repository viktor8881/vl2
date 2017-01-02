<?php


class Model_CardCurrencyBalance {
        
    private $balance;
    private $sumInvest;    
    private $currentCourse;
        
    private $_sumPay;
    
    /**
     * 
     * @return BalanceCurrency_Model
     */
    public function getBalance() {
        return $this->balance;
    }
    
    public function getValueBalance() {
        $balance = $this->getBalance();
        return ($balance)?$balance->getBalance():0;
    }

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
    
    public function getValueCurrentCourse() {
        $course = $this->getCurrentCourse();
        return ($course)?$course->getValueForOne():'0';
    }

    public function setBalance(BalanceCurrency_Model $balance) {
        $this->balance = $balance;
        return $this;
    }

    public function setSumInvest($sumInvest) {
        $this->sumInvest = $sumInvest;
        return $this;
    }

    public function setCurrentCourse(CourseCurrency_Model $currentCourse) {
        $this->currentCourse = $currentCourse;
        return $this;
    }
    
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
    
    public function diffSumInvestToPercent() {
        return $this->getSumInvest()*100/$this->getSumPay();
    }
    
    public function diffSumInvest() {
        return $this->getSumPay()-$this->getSumInvest();
    }

    public function isInvestUp() {
        return $this->diffSumInvest() >= 0;
    }
    
    public function isInvestDown() {
        return $this->diffSumInvest() < 0;
    }
    
}
