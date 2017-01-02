<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model_CardMetalBalance
 *
 * @author Home
 */
class Model_CardMetalBalance {
        
    private $balance;
    private $sumInvest;    
    private $currentCourse;
    
    private $_sumPay;
    
    
    /**
     * 
     * @return BalanceMetal_Model
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
     * @return CourseMetal_Model
     */
    public function getMetalCourse() {
        return $this->currentCourse;
    }

    public function getValueMetalCourse() {
        $course = $this->getMetalCourse();
        return ($course)?$course->getBuy():'-';
    }
    
    public function setBalance(BalanceMetal_Model $balance) {
        $this->balance = $balance;
        return $this;
    }

    public function setSumInvest($sumInvest) {
        $this->sumInvest = $sumInvest;
        return $this;
    }

    public function setCurrentCourse(CourseMetal_Model $currentCourse) {
        $this->currentCourse = $currentCourse;
        return $this;
    }

    public function getMetalName() {
        $balance = $this->getBalance();
        if ($balance) {
            return $balance->getMetalName();
        }
        return '';
    }
    
    /**
     * Сумма продажи по текущему курсу
     * @return float
     */
    public function getSumPay() {
        if (is_null($this->_sumPay)) {
            $this->_sumPay = $this->getValueBalance() * $this->getValueMetalCourse();
        }
        return $this->_sumPay;
    }
    
    public function diffSumInvest() {
        return $this->getSumPay()-$this->getSumInvest();
    }
    
    public function diffSumInvestToPercent() {
        return $this->getSumInvest()*100/$this->getSumPay();
    }

    public function isInvestUp() {
        return $this->diffSumInvest() >= 0;
    }
    
    public function isInvestDown() {
        return $this->diffSumInvest() < 0;
    }
    
}
