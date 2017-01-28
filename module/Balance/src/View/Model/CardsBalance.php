<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CardsBalance
 *
 * @author Home
 */
class Model_CardsBalance {

    /**
     * @var array
     */
    private $balanceMetals=array();
    /**
     * @var array
     */
    private $balanceCurrencies=array();

    /**
     * @return array
     */
    public function getBalanceMetals() {
        return $this->balanceMetals;
    }

    /**
     * @return array
     */
    public function getBalanceCurrencies() {
        return $this->balanceCurrencies;
    }

    /**
     * @param Model_CardMetalBalance $balance
     *
     * @return $this
     */
    public function addBalanceMetal(Model_CardMetalBalance $balance) {
        $this->balanceMetals[] = $balance;
        return $this;
    }

    /**
     * @param Model_CardCurrencyBalance $balance
     *
     * @return $this
     */
    public function addBalanceCurrency(Model_CardCurrencyBalance $balance) {
        $this->balanceCurrencies[] = $balance;
        return $this;
    }

}
