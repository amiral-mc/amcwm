<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockInfo
 *
 * @author Abdallah
 */
class StockInfoGraph extends Dataset {

    private $_exchangeId;

    public function __construct($exchangeId, $limit = null) {
        $this->_exchangeId = $exchangeId;
        if ($limit !== NULL) {
            $this->limit = (int) $limit;
        } else {
            $this->limit = null;
        }
    }

    public function generate() {
        $this->setItems();
    }

    protected function setItems() {
        $currentDate = date("Y-m-d");
        $cols = $this->generateColumns();
//        $wheres = sprintf("exchange_date = '{$currentDate}' AND exchange_id = {$this->_exchangeId}");
        $wheres = sprintf('exchange_date = "' . date("Y-m-d", strtotime(date("Y-m-d") . "-6 days")) . '" AND e.exchange_id = ' . $this->_exchangeId);
        $wheres .= $this->generateWheres();
        $this->query = AmcWm::app()->db->createCommand();
        $this->query->from("exchange e");
        $this->query->join = 'inner join exchange_trading et on e.exchange_id = et.exchange_id';
        $this->query->join .= $this->joins . " ";
        $this->query->select("exchange_name, exchange_date, trading_value, shares_of_stock, closing_value, difference_value, difference_percentage $cols");
        $this->query->where($wheres);
        if ($this->limit !== null) {
            $this->query->limit($this->limit, $this->fromRecord);
        }
        $this->count = Yii::app()->db->createCommand("select count(*) from exchange e {$this->query->join} where {$this->query->where}")->queryScalar();
        $this->items = $this->query->queryAll();
    }

    public function getRow($index) {
        if (isset($this->items[$index])) {
            return $this->items[$index];
        }
    }

}
