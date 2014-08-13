<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AmcStockController
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcStockController extends FrontendController {

    public function actionStockDetails() {
        $data = array();
        $dates = array();
        $closingValues = array();
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoGraph($exchangeId);
        $stock->generate();
        $stockData = $stock->getRow(0);

        $exchangeData = $stock->graphData();
        //@TODO Check why RGRaph does not work with decimals in this scenario, having to use round below
        foreach ($exchangeData as $key => $value) {
            $dates[] = date("d M", strtotime($value['exchange_date']));
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
        $stock = new StockInfoTicker($exchangeId);
        $stock->generate();
        $data = $stock->getData();
        $output = "";
        if ($isJson && $data) {
            $data = array_slice($data, 0, $companiesLimit);
            echo $this->renderPartial('companyGrid', array('data' => $data), true);
        } else {
            echo $this->renderPartial('ticker', array('data' => $data, 'rowLimit' => $rowLimit), true);
        }
        Yii::app()->end();
    }

}
