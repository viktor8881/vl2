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
class Model_CardMetalBalance
{

    /**
     * @var float
     */
    private $balance;
    /**
     * @var float
     */
    private $sumInvest;
    /**
     * @var
     */
    private $currentCourse;

    /**
     * @var float
     */
    private $_sumPay;


    /**
     *
     * @return BalanceMetal_Model
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getValueBalance()
    {
        $balance = $this->getBalance();
        return ($balance) ? $balance->getBalance() : 0;
    }

    /**
     * @return mixed
     */
    public function getSumInvest()
    {
        return $this->sumInvest;
    }

    /**
     *
     * @return CourseMetal_Model
     */
    public function getMetalCourse()
    {
        return $this->currentCourse;
    }

    /**
     * @return string
     */
    public function getValueMetalCourse()
    {
        $course = $this->getMetalCourse();
        return ($course) ? $course->getBuy() : '-';
    }

    /**
     * @param BalanceMetal_Model $balance
     *
     * @return $this
     */
    public function setBalance(BalanceMetal_Model $balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @param float $sumInvest
     *
     * @return $this
     */
    public function setSumInvest($sumInvest)
    {
        $this->sumInvest = $sumInvest;
        return $this;
    }

    /**
     * @param CourseMetal_Model $currentCourse
     *
     * @return $this
     */
    public function setCurrentCourse(CourseMetal_Model $currentCourse)
    {
        $this->currentCourse = $currentCourse;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetalName()
    {
        $balance = $this->getBalance();
        if ($balance) {
            return $balance->getMetalName();
        }
        return '';
    }

    /**
     * Сумма продажи по текущему курсу
     *
     * @return float
     */
    public function getSumPay()
    {
        if (is_null($this->_sumPay)) {
            $this->_sumPay = $this->getValueBalance()
                * $this->getValueMetalCourse();
        }
        return $this->_sumPay;
    }

    /**
     * @return float
     */
    public function diffSumInvest()
    {
        return $this->getSumPay() - $this->getSumInvest();
    }

    /**
     * @return float
     */
    public function diffSumInvestToPercent()
    {
        return $this->getSumInvest() * 100 / $this->getSumPay();
    }

    /**
     * @return bool
     */
    public function isInvestUp()
    {
        return $this->diffSumInvest() >= 0;
    }

    /**
     * @return bool
     */
    public function isInvestDown()
    {
        return $this->diffSumInvest() < 0;
    }

}
