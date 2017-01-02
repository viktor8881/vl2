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
        
    private $balanceMetals=array();    
    private $balanceCurrencies=array();
    
    public function getBalanceMetals() {
        return $this->balanceMetals;
    }

    public function getBalanceCurrencies() {
        return $this->balanceCurrencies;
    }

    public function addBalanceMetal(Model_CardMetalBalance $balance) {
        $this->balanceMetals[] = $balance;
        return $this;
    }

    public function addBalanceCurrency(Model_CardCurrencyBalance $balance) {
        $this->balanceCurrencies[] = $balance;
        return $this;
    }
    
    
    
}
