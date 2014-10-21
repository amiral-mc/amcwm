<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * StockInfoTicker
 * @author Amiral Management Corporation
 * @version 1.0
 */
class StockInfoTicker extends Dataset {

    private $_exchangeId;

    public function __construct($exchangeId, $limit = null) {
        $this->_exchangeId = (int)$exchangeId;
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
        $stock = new StockInfoGraph($this->_exchangeId, 1);
        $stock->generate();
        $stockData = $stock->getRow(0);
        if (isset($stockData['exchange_date'])) {
            $wheres = "exchange_trading_exchange_id = {$this->_exchangeId}";
            $this->addWhere("e.published = 1");
            $this->addWhere("ect.content_lang = " . Yii::app()->db->quoteValue(Yii::app()->getLanguage()));
            $this->addWhere("etc.exchange_trading_exchange_date = '{$stockData['exchange_date']}'");
            $wheres .= $this->generateWheres();
            $this->query = AmcWm::app()->db->createCommand();
            $this->query->from("exchange_trading_companies etc");
            $this->query->join = 'inner join exchange_companies ec on exchange_companies_exchange_companies_id = ec.exchange_companies_id';
            $this->query->join .= ' inner join exchange_companies_translation ect on ec.exchange_companies_id = ect.exchange_companies_id';
            $this->query->join .= ' inner join exchange e on ec.exchange_id = e.exchange_id ';
            $this->query->join .= $this->joins . " ";
            $this->query->select("e.exchange_name, ect.company_name, opening_value, etc.closing_value, etc.difference_percentage $cols");
            $this->query->where($wheres);
            if ($this->limit !== null) {
                $this->query->limit($this->limit, $this->fromRecord);
            }
            $this->count = Yii::app()->db->createCommand("select count(*) from exchange_trading_companies etc {$this->query->join} where {$this->query->where}")->queryScalar();
            $this->items = $this->query->queryAll();
        }
    }

}
