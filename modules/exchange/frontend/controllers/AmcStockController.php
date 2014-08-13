<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockInfoController
 *
 * @author Abdallah
 */
class AmcStockController extends FrontendController {

    public function actionStockDetails() {
//        $settings = Data::getInstance()->getSettings('exchange');
//        $graphDaysLimit = $settings->settings['frontend']['options']['graphDaysLimit'];
        $data = array();
        $dates = array();
        $closingValues = array();
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoGraph($exchangeId);
        $stock->generate();
        $stockData = $stock->getRow(0);
        
        $exchangeData = $stock->graphData();
//        $exchangeData = Yii::app()->db->createCommand()
//                ->select('exchange_date, closing_value')
//                ->from('exchange_trading')
//                ->where('exchange_id =' . $exchangeId)
//                ->order('exchange_date ASC')
//                ->limit($graphDaysLimit)
//                ->queryAll();
        //@TODO Check why RGRaph does not work with decimals in this scenario, having to use round below
        foreach ($exchangeData as $key => $value) {
            $dates[] = $value['exchange_date'];
            $closingValues[] = round($value['closing_value']);
        }
        $data['latest'] = $stockData;
        $data['labels'] = $dates;
        $data['values'] = $closingValues;
        echo json_encode($data);
        Yii::app()->end();
    }

    public function actionStock() {
        $settings = Data::getInstance()->getSettings('exchange');
        $rowLimit = $settings->settings['frontend']['options']['tickerLimit'];
        $companiesLimit = $settings->settings['frontend']['options']['companiesGridLimit'];
        $isJson = Yii::app()->request->getParam('is_json', 0);
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoTicker($exchangeId, $companiesLimit);
        $stock->generate();
        $data = $stock->getData();
        $output = "";
        if ($isJson && $data) {
            echo $this->renderPartial('companyGrid', array('data' => $data), true);
        } else {
            echo $this->renderPartial('ticker', array('data' => $data, 'rowLimit' => $rowLimit), true);
        }
        Yii::app()->end();
    }

}
